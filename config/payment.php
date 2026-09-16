<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Mode
    |--------------------------------------------------------------------------
    |
    | Determines which payment service to use. Options:
    | - 'mock': Uses MockPaymentService for development/testing
    | - 'production': Uses real payment provider (Midtrans/Xendit/Tripay)
    |
    */
    'mode' => env('PAYMENT_MODE', 'mock'),

    /*
    |--------------------------------------------------------------------------
    | Payment Provider
    |--------------------------------------------------------------------------
    |
    | The payment provider to use in production mode.
    | Options: 'midtrans', 'xendit', 'tripay'
    |
    */
    'provider' => env('PAYMENT_PROVIDER', ''),

    /*
    |--------------------------------------------------------------------------
    | API Credentials
    |--------------------------------------------------------------------------
    */
    'api_key' => env('PAYMENT_API_KEY', ''),
    'secret' => env('PAYMENT_SECRET', ''),
    'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Payment Expiry
    |--------------------------------------------------------------------------
    |
    | How long (in minutes) before a payment expires.
    |
    */
    'expiry_minutes' => (int) env('PAYMENT_EXPIRY_MINUTES', 30),

    /*
    |--------------------------------------------------------------------------
    | Mock Payment Settings
    |--------------------------------------------------------------------------
    */
    'mock' => [
        'auto_expire' => true,
        'simulate_delay' => false,
    ],
];
