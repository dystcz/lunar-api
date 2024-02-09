<?php

/*
 * Lunar API general configuration
 */
return [
    // Prefix for all the API routes
    // Leave empty if you don't want to use a prefix
    'route_prefix' => 'api',

    // Middleware for all the API routes
    'route_middleware' => ['api'],

    // Additional Lunar API compatible servers
    'additional_servers' => [
        //
    ],

    // Enable or disable hashids
    'use_hashids' => env('LUNAR_API_USE_HASHIDS', false),

    // Pagination defaults
    'pagination' => [
        'per_page' => 24,
        'max_size' => 48,
    ],

    // Tax defaults
    'taxation' => [
        'prices_with_default_tax' => true,
    ],

    // Checkout settings
    'checkout' => [
        // Forget cart right after order is created
        'forget_cart_after_order_creation' => true,

        // Allow multiple orders per cart
        'multiple_orders_per_cart' => false,

        // Protection strategy for checkout routes
        // Available strategies: signature, auth, null
        'checkout_protection_strategy' => 'signature',
    ],

];
