<?php

namespace Dystcz\LunarApi\Tests\Feature\PaymentOptions;

use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

uses(TestCase::class, RefreshDatabase::class);

it('can fetch payment options', function () {
    $response = $this
        ->jsonApi()
        ->expects('payment-options')
        ->get('/api/v1/payment-options');

    $response->assertSuccessful();

    $paymentOption = Config::get('lunar.payments.types');

    $response->assertFetchedMany(
        array_map(function ($paymentOption, $paymentOptionName) {
            return [
                'type' => 'payment-options',
                'id' => $paymentOption['driver'],
                'attributes' => [
                    'driver' => $paymentOption['driver'],
                    'name' => $paymentOptionName,
                    'default' => $paymentOption['driver'] === Config::get('lunar.payments.default'),
                ],
            ];
        }, $paymentOption, array_keys($paymentOption))
    );
})->group('payment-options');
