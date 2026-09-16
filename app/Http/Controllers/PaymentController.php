<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Services\Payment\PaymentGatewayInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly PaymentGatewayInterface $paymentGateway,
    ) {}

    public function show(Order $order): View
    {
        if ($order->user_id && $order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'payment']);
        $payment = $order->payment;

        return view('payment.show', compact('order', 'payment'));
    }

    public function checkStatus(Order $order): JsonResponse
    {
        if ($order->user_id && $order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $order->load('payment');
        $payment = $order->payment;

        $status = $order->status;

        // Also check via gateway if payment is still pending
        if ($payment && $payment->status === 'pending') {
            $gatewayStatus = $this->paymentGateway->getPaymentStatus($payment->reference_id);
            if ($gatewayStatus['success'] && $gatewayStatus['status'] !== 'pending') {
                $this->orderService->processWebhookPayment(
                    $payment->reference_id,
                    $gatewayStatus['status'],
                    null,
                    $gatewayStatus['paid_at'],
                );
                $order->refresh();
                $status = $order->status;
            }
        }

        return response()->json([
            'success' => true,
            'status' => $status,
            'order_id' => $order->id,
        ]);
    }

    public function cancel(Order $order): RedirectResponse
    {
        if ($order->user_id && $order->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $this->orderService->cancelOrder($order);

            return redirect()->route('home')->with('success', 'Pesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan pesanan.');
        }
    }

    public function success(Order $order): View
    {
        if ($order->user_id && $order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'payment']);

        return view('payment.success', compact('order'));
    }

    public function failed(Order $order): View
    {
        if ($order->user_id && $order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'payment']);

        return view('payment.failed', compact('order'));
    }
}
