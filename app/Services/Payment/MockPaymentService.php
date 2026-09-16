<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MockPaymentService implements PaymentGatewayInterface
{
    /**
     * Create a mock payment with a generated QR code.
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
    public function createPayment(Order $order): array
    {
        $referenceId = 'MOCK-'.strtoupper(Str::random(16));
        $expiryMinutes = (int) config('payment.expiry_minutes', 30);
        $expiredAt = Carbon::now()->addMinutes($expiryMinutes);

        $qrCode = $this->generateMockQrCode($referenceId, $order->total);

        return [
            'success' => true,
            'reference_id' => $referenceId,
            'transaction_id' => 'TXN-'.strtoupper(Str::random(12)),
            'payment_url' => null,
            'qr_code' => $qrCode,
            'qr_string' => $referenceId,
            'amount' => (float) $order->total,
            'expired_at' => $expiredAt->toIso8601String(),
            'raw_response' => [
                'mode' => 'mock',
                'created_at' => now()->toIso8601String(),
            ],
        ];
    }

    /**
     * Get mock payment status — checks our local database.
     *
     * @return array{
     *     success: bool,
     *     status: string,
     *     paid_at: string|null,
     *     raw_response: array|null,
     * }
     */
    public function getPaymentStatus(string $referenceId): array
    {
        $payment = Payment::where('reference_id', $referenceId)->first();

        if (! $payment) {
            return [
                'success' => false,
                'status' => 'not_found',
                'paid_at' => null,
                'raw_response' => null,
            ];
        }

        // Check if expired
        if ($payment->expired_at && Carbon::now()->isAfter($payment->expired_at) && $payment->status === Payment::STATUS_PENDING) {
            return [
                'success' => true,
                'status' => Payment::STATUS_EXPIRED,
                'paid_at' => null,
                'raw_response' => ['mode' => 'mock'],
            ];
        }

        return [
            'success' => true,
            'status' => $payment->status,
            'paid_at' => $payment->paid_at?->toIso8601String(),
            'raw_response' => ['mode' => 'mock'],
        ];
    }

    /**
     * Handle mock webhook — admin triggers success/failure.
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
    public function handleWebhook(Request $request): array
    {
        $referenceId = $request->input('reference_id');
        $status = $request->input('status', 'paid');
        $amount = $request->input('amount');

        return [
            'success' => true,
            'reference_id' => $referenceId,
            'status' => $status,
            'amount' => $amount ? (float) $amount : null,
            'paid_at' => $status === 'paid' ? now()->toIso8601String() : null,
            'transaction_id' => $request->input('transaction_id'),
        ];
    }

    /**
     * Cancel a mock payment.
     */
    public function cancelPayment(string $referenceId): bool
    {
        return true;
    }

    /**
     * Verify mock webhook — in mock mode, check for a simple token.
     */
    public function verifyWebhookSignature(Request $request): bool
    {
        // In mock mode, accept a simple token or always return true for admin-triggered webhooks
        $secret = config('payment.webhook_secret');

        if (empty($secret)) {
            return true;
        }

        return $request->header('X-Webhook-Secret') === $secret;
    }

    /**
     * Generate a mock QR code as a base64 SVG image.
     */
    private function generateMockQrCode(string $referenceId, float $amount): string
    {
        $formattedAmount = 'Rp '.number_format($amount, 0, ',', '.');

        $svg = <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" width="300" height="340" viewBox="0 0 300 340">
            <rect width="300" height="340" fill="#122235" rx="16"/>
            <rect x="20" y="20" width="260" height="260" fill="#0B1725" rx="12"/>
            <!-- QR Pattern Mock -->
            <g transform="translate(40, 40)" fill="#00E5D4">
                <!-- Top-left position marker -->
                <rect x="0" y="0" width="56" height="56" rx="4"/>
                <rect x="8" y="8" width="40" height="40" fill="#0B1725" rx="2"/>
                <rect x="16" y="16" width="24" height="24" rx="2"/>
                <!-- Top-right position marker -->
                <rect x="164" y="0" width="56" height="56" rx="4"/>
                <rect x="172" y="8" width="40" height="40" fill="#0B1725" rx="2"/>
                <rect x="180" y="16" width="24" height="24" rx="2"/>
                <!-- Bottom-left position marker -->
                <rect x="0" y="164" width="56" height="56" rx="4"/>
                <rect x="8" y="172" width="40" height="40" fill="#0B1725" rx="2"/>
                <rect x="16" y="180" width="24" height="24" rx="2"/>
                <!-- Data pattern -->
                <rect x="72" y="8" width="8" height="8"/>
                <rect x="88" y="8" width="8" height="8"/>
                <rect x="104" y="16" width="8" height="8"/>
                <rect x="120" y="8" width="8" height="8"/>
                <rect x="136" y="24" width="8" height="8"/>
                <rect x="72" y="32" width="8" height="8"/>
                <rect x="96" y="40" width="8" height="8"/>
                <rect x="112" y="48" width="8" height="8"/>
                <rect x="128" y="32" width="8" height="8"/>
                <rect x="144" y="40" width="8" height="8"/>
                <rect x="8" y="72" width="8" height="8"/>
                <rect x="24" y="80" width="8" height="8"/>
                <rect x="40" y="72" width="8" height="8"/>
                <rect x="72" y="72" width="8" height="8"/>
                <rect x="88" y="80" width="8" height="8"/>
                <rect x="104" y="72" width="8" height="8"/>
                <rect x="120" y="88" width="8" height="8"/>
                <rect x="136" y="72" width="8" height="8"/>
                <rect x="152" y="80" width="8" height="8"/>
                <rect x="168" y="72" width="8" height="8"/>
                <rect x="184" y="88" width="8" height="8"/>
                <rect x="200" y="72" width="8" height="8"/>
                <rect x="72" y="104" width="8" height="8"/>
                <rect x="96" y="112" width="8" height="8"/>
                <rect x="112" y="104" width="8" height="8"/>
                <rect x="128" y="120" width="8" height="8"/>
                <rect x="144" y="104" width="8" height="8"/>
                <rect x="72" y="136" width="8" height="8"/>
                <rect x="88" y="144" width="8" height="8"/>
                <rect x="104" y="136" width="8" height="8"/>
                <rect x="120" y="152" width="8" height="8"/>
                <rect x="136" y="136" width="8" height="8"/>
                <rect x="152" y="144" width="8" height="8"/>
                <rect x="168" y="136" width="8" height="8"/>
                <rect x="184" y="152" width="8" height="8"/>
                <rect x="200" y="136" width="8" height="8"/>
                <rect x="72" y="168" width="8" height="8"/>
                <rect x="88" y="176" width="8" height="8"/>
                <rect x="104" y="168" width="8" height="8"/>
                <rect x="128" y="184" width="8" height="8"/>
                <rect x="152" y="168" width="8" height="8"/>
                <rect x="168" y="192" width="8" height="8"/>
                <rect x="184" y="168" width="8" height="8"/>
                <rect x="200" y="184" width="8" height="8"/>
            </g>
            <!-- MOCK label -->
            <text x="150" y="300" text-anchor="middle" fill="#7A2CFF" font-family="monospace" font-size="11" font-weight="bold">MOCK QRIS — {$referenceId}</text>
            <text x="150" y="322" text-anchor="middle" fill="#00E5D4" font-family="sans-serif" font-size="14" font-weight="bold">{$formattedAmount}</text>
        </svg>
        SVG;

        return 'data:image/svg+xml;base64,'.base64_encode(trim($svg));
    }
}
