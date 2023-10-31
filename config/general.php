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

    'auth' => [
        'actions' => [
            'create_user_from_cart' => Dystcz\LunarApi\Domain\Carts\Actions\CreateUserFromCart::class,
            'register_user' => Dystcz\LunarApi\Domain\Users\Actions\RegisterUser::class,
        ],

        'notifications' => [
            'reset_password' => Illuminate\Auth\Notifications\ResetPassword::class,
            'verify_email' => Illuminate\Auth\Notifications\VerifyEmail::class,
        ],
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

];
