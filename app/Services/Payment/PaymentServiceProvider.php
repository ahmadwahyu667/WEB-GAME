<?php

namespace App\Services\Payment;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register the payment gateway service.
     */
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayInterface::class, function ($app) {
            $mode = config('payment.mode', 'mock');

            return match ($mode) {
                'mock' => new MockPaymentService,
                // Future production providers:
                // 'midtrans' => new MidtransPaymentService,
                // 'xendit' => new XenditPaymentService,
                // 'tripay' => new TripayPaymentService,
                default => new MockPaymentService,
            };
        });
    }

    /**
     * Bootstrap the payment service.
     */
    public function boot(): void
    {
        //
    }
}
