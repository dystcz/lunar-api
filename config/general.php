<?php

use Dystcz\LunarApi\Domain\Checkout\Enums\CheckoutProtectionStrategy;

/*
 * Lunar API general configuration
 */
return [
    // Prefix for all the API routes
    // Leave empty if you don't want to use a prefix
    'route_prefix' => 'api',

    // Middleware for all the API routes
    'route_middleware' => ['api'],

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

    // Purchasable
    'purchasable' => [
        'non_eloquent_types' => [
            'shipping',
        ],
    ],

    // Checkout settings
    'checkout' => [
        // Forget cart right after order is created
        'forget_cart_after_order_creation' => true,

        // Allow multiple orders per cart
        'multiple_orders_per_cart' => false,

        // Protection strategy for checkout routes
        // Available strategies: signature, auth, null
        'checkout_protection_strategy' => CheckoutProtectionStrategy::SIGNATURE,
    ],
];
