<?php

use Dystcz\LunarApi\Domain\Payments\Contracts\HandlePaymentWebhookController;
use Dystcz\LunarApi\Support\Config\Actions\RegisterRoutesFromConfig;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => Config::get('lunar-api.general.route_prefix'),
    'middleware' => Config::get('lunar-api.general.route_middleware'),
], fn () => RegisterRoutesFromConfig::run());

// Payments
Route::post('{paymentDriver}/webhook', HandlePaymentWebhookController::class)
    ->name('payments.webhook');
