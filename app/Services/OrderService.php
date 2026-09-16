<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentLog;
use App\Models\Product;
use App\Services\Payment\PaymentGatewayInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        private PaymentGatewayInterface $paymentGateway,
    ) {}

    /**
     * Create an order from cart items. Uses DB transaction and pessimistic locking.
     *
     * @param  array<int, array{product_id: int, quantity: int}>  $items
     * @return array{order: Order, payment: Payment}
     */
    public function createOrder(int $userId, array $items): array
    {
        return DB::transaction(function () use ($userId, $items) {
            $subtotal = 0;
            $discount = 0;
            $orderItems = [];

            foreach ($items as $item) {
                // Lock the product row to prevent race conditions on stock
                $product = Product::where('id', $item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (! $product) {
                    throw new \RuntimeException("Produk dengan ID {$item['product_id']} tidak ditemukan.");
                }

                if ($product->status !== 'active') {
                    throw new \RuntimeException("Produk \"{$product->name}\" tidak aktif.");
                }

                if ($product->stock < $item['quantity']) {
                    throw new \RuntimeException(
                        "Stok \"{$product->name}\" tidak mencukupi. Tersedia: {$product->stock}."
                    );
                }

                // Calculate prices server-side (NEVER trust frontend)
                $originalPrice = (float) $product->price;
                $effectivePrice = $product->discount_price
                    ? (float) $product->discount_price
                    : $originalPrice;
                $itemSubtotal = $effectivePrice * $item['quantity'];
                $itemDiscount = ($originalPrice - $effectivePrice) * $item['quantity'];

                $subtotal += $originalPrice * $item['quantity'];
                $discount += $itemDiscount;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name_snapshot' => $product->name,
                    'price_snapshot' => $effectivePrice,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
                ];

                // Deduct stock
                $product->decrement('stock', $item['quantity']);
                $product->increment('sold_count', $item['quantity']);
            }

            $total = $subtotal - $discount;

            // Generate unique order number
            $orderNumber = Order::generateOrderNumber();

            // Create the order
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => $orderNumber,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => Order::STATUS_PENDING,
            ]);

            // Create order items
            foreach ($orderItems as $orderItem) {
                OrderItem::create(array_merge($orderItem, [
                    'order_id' => $order->id,
                ]));
            }

            // Create payment via gateway
            $paymentResult = $this->paymentGateway->createPayment($order);

            if (! $paymentResult['success']) {
                throw new \RuntimeException('Gagal membuat pembayaran. Silakan coba lagi.');
            }

            $payment = Payment::create([
                'order_id' => $order->id,
                'provider' => config('payment.mode') === 'mock' ? 'mock' : config('payment.provider'),
                'payment_method' => 'qris',
                'transaction_id' => $paymentResult['transaction_id'],
                'reference_id' => $paymentResult['reference_id'],
                'amount' => $paymentResult['amount'],
                'status' => Payment::STATUS_PENDING,
                'payment_url' => $paymentResult['payment_url'],
                'qr_code' => $paymentResult['qr_code'],
                'expired_at' => Carbon::parse($paymentResult['expired_at']),
                'raw_response' => $paymentResult['raw_response'],
            ]);

            // Update order status
            $order->update([
                'status' => Order::STATUS_WAITING_PAYMENT,
                'expired_at' => $payment->expired_at,
            ]);

            // Log payment creation
            PaymentLog::create([
                'payment_id' => $payment->id,
                'event' => 'payment.created',
                'status' => Payment::STATUS_PENDING,
                'payload_summary' => "Payment created for order {$orderNumber}",
                'received_at' => now(),
            ]);

            return [
                'order' => $order->load('items'),
                'payment' => $payment,
            ];
        });
    }

    /**
     * Handle a verified webhook event.
     */
    public function processWebhookPayment(string $referenceId, string $status, ?float $amount = null, ?string $paidAt = null): bool
    {
        return DB::transaction(function () use ($referenceId, $status, $amount, $paidAt) {
            $payment = Payment::where('reference_id', $referenceId)
                ->lockForUpdate()
                ->first();

            if (! $payment) {
                Log::warning("Webhook received for unknown reference: {$referenceId}");

                return false;
            }

            // Idempotency: if already processed with same status, skip
            if ($payment->status === $status) {
                Log::info("Duplicate webhook for reference {$referenceId} with status {$status}");

                return true;
            }

            // Don't allow status changes from terminal states
            $terminalStatuses = [Payment::STATUS_PAID, Payment::STATUS_REFUNDED];
            if (in_array($payment->status, $terminalStatuses) && $status !== Payment::STATUS_REFUNDED) {
                Log::warning("Attempted to change terminal payment status for reference {$referenceId}");

                return false;
            }

            // Validate amount matches if provided
            if ($amount !== null && abs($amount - (float) $payment->amount) > 0.01) {
                Log::warning("Amount mismatch for reference {$referenceId}: expected {$payment->amount}, got {$amount}");

                return false;
            }

            // Update payment status
            $updateData = ['status' => $status];
            if ($status === Payment::STATUS_PAID) {
                $updateData['paid_at'] = $paidAt ? Carbon::parse($paidAt) : now();
            }
            $payment->update($updateData);

            // Update order status accordingly
            $order = $payment->order()->lockForUpdate()->first();
            if ($order) {
                $orderStatus = match ($status) {
                    Payment::STATUS_PAID => Order::STATUS_PAID,
                    Payment::STATUS_FAILED => Order::STATUS_FAILED,
                    Payment::STATUS_EXPIRED => Order::STATUS_EXPIRED,
                    Payment::STATUS_CANCELLED => Order::STATUS_CANCELLED,
                    default => null,
                };

                if ($orderStatus) {
                    $orderUpdateData = ['status' => $orderStatus];
                    if ($orderStatus === Order::STATUS_PAID) {
                        $orderUpdateData['paid_at'] = $payment->paid_at;
                    }
                    $order->update($orderUpdateData);
                }

                // Return stock if payment failed/expired/cancelled
                if (in_array($status, [Payment::STATUS_FAILED, Payment::STATUS_EXPIRED, Payment::STATUS_CANCELLED])) {
                    $this->restoreStock($order);
                }
            }

            // Log the webhook event
            PaymentLog::create([
                'payment_id' => $payment->id,
                'event' => 'webhook.'.$status,
                'status' => $status,
                'payload_summary' => "Webhook processed: status changed to {$status}",
                'received_at' => now(),
            ]);

            return true;
        });
    }

    /**
     * Cancel an order and its payment.
     */
    public function cancelOrder(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            $payment = $order->payment;

            if ($payment && in_array($payment->status, [Payment::STATUS_PENDING])) {
                $this->paymentGateway->cancelPayment($payment->reference_id);
                $payment->update(['status' => Payment::STATUS_CANCELLED]);

                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'event' => 'payment.cancelled',
                    'status' => Payment::STATUS_CANCELLED,
                    'payload_summary' => 'Payment cancelled by user',
                    'received_at' => now(),
                ]);
            }

            $order->update(['status' => Order::STATUS_CANCELLED]);
            $this->restoreStock($order);

            return true;
        });
    }

    /**
     * Restore stock when order is cancelled/expired/failed.
     */
    private function restoreStock(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_id) {
                Product::where('id', $item->product_id)
                    ->increment('stock', $item->quantity);
                Product::where('id', $item->product_id)
                    ->decrement('sold_count', $item->quantity);
            }
        }
    }

    /**
     * Check and expire old pending payments.
     */
    public function expireOldPayments(): int
    {
        $expiredPayments = Payment::where('status', Payment::STATUS_PENDING)
            ->where('expired_at', '<', now())
            ->get();

        $count = 0;
        foreach ($expiredPayments as $payment) {
            $this->processWebhookPayment($payment->reference_id, Payment::STATUS_EXPIRED);
            $count++;
        }

        return $count;
    }
}
