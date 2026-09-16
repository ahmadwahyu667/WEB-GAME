<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * Create a new payment for the given order.
     *
     * @return array{
     *     success: bool,
     *     reference_id: string,
     *     transaction_id: string|null,
     *     payment_url: string|null,
     *     qr_code: string|null,
     *     qr_string: string|null,
     *     amount: float,
     *     expired_at: string,
     *     raw_response: array|null,
     * }
     */
    public function createPayment(Order $order): array;

    /**
     * Get payment status from the provider.
     *
     * @return array{
     *     success: bool,
     *     status: string,
     *     paid_at: string|null,
     *     raw_response: array|null,
     * }
     */
    public function getPaymentStatus(string $referenceId): array;

    /**
     * Handle incoming webhook from the payment provider.
     *
     * @return array{
     *     success: bool,
     *     reference_id: string|null,
     *     status: string,
     *     amount: float|null,
     *     paid_at: string|null,
     *     transaction_id: string|null,
     * }
     */
    public function handleWebhook(Request $request): array;

    /**
     * Cancel a pending payment.
     */
    public function cancelPayment(string $referenceId): bool;

    /**
     * Verify webhook signature/authentication.
     */
    public function verifyWebhookSignature(Request $request): bool;
}
