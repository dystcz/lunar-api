<?php

/**
 * Product routes.
 */
use Domain\Products\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [],
], function () {
    Route::get('/', ProductsController::class);
});
