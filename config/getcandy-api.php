<?php

/*
 * Getcandy API configuration
 */
return [

    // Prefix for all the API routes
    // Leave empty if you don't want to use a prefix
    'route_prefix' => 'api',

    // Here you can define the API controllers
    // If you want to change a behaviour or add some data,
    // simply extend the package controller and add your logic
    'controllers' => [
        'products' => Dystcz\GetcandyApi\Domain\Products\Http\Controllers\ProductsController::class,
    ],

];
