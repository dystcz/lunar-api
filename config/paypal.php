<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    /**
     * Configuration for srmklive/paypal package
     */
    'mode' => env('PAYPAL_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.

    'sandbox' => [
        'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET', ''),
        'app_id' => 'APP-80W284485P519543T',
    ],
    'live' => [
        'client_id' => env('PAYPAL_LIVE_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
        'app_id' => '',
    ],

    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Can only be 'Sale', 'Authorization' or 'Order'
    'currency' => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url' => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
    'locale' => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl' => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating api client.

    /**
     * PayPal orders configuration,
     * for more info see https://developer.paypal.com/docs/api/orders/v2/#orders_create!path=application_context/return_url&t=request
     */
    'return_url' => env('PAYPAL_RETURN_URL', ''),
    'cancel_url' => env('PAYPAL_CANCEL_URL', ''),

    /**
     * Webhooks Configuration
     */
    'webhook' => [
        'id' => env('PAYPAL_WEBHOOK_ID', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Capture policy
    |--------------------------------------------------------------------------
    |
    | Here is where you can set whether you want to capture and charge payments
    | straight away, or create the Payment Intent and release them at a later date.
    |
    | automatic - Capture the payment straight away.
    | manual - Don't take payment straight away and capture later.
    |
    */
    'policy' => 'automatic',
];
