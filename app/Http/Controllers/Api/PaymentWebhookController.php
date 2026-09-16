<?php

namespace App\Http\Controllers\Api;

use App\Contracts\PaymentGatewayInterface;
use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly PaymentGatewayInterface $paymentGateway
    ) {}

    public function handle(Request $request): JsonResponse
    {
        try {
            if (! $this->paymentGateway->verifyWebhookSignature($request)) {
                Log::warning('Invalid payment webhook signature', ['ip' => $request->ip()]);

                return response()->json([
                    'success' => false,
                    'message' => 'Tanda tangan webhook tidak valid',
                    'data' => null,
                ], 400);
            }

            $webhookData = $this->paymentGateway->parseWebhookData($request);

            $this->orderService->processWebhookPayment($webhookData);

            Log::info('Payment webhook processed successfully', ['order_id' => $webhookData['order_id'] ?? null]);

            return response()->json([
                'success' => true,
                'message' => 'Webhook diproses',
                'data' => null,
            ]);
        } catch (\Exception $e) {
            Log::error('Payment webhook error: '.$e->getMessage(), [
                'payload' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses webhook',
                'data' => null,
            ], 500);
        }
    }
}
