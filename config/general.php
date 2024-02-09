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
