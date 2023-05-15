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

    $paymentOption = Config::get('lunar.payments.types')['card'];

    $response->assertFetchedMany([
        [
            'type' => 'payment-options',
            'id' => $paymentOption['driver'],
            'attributes' => [
                'driver' => $paymentOption['driver'],
                'name' => 'card',
                'default' => true,
            ],
        ],
    ]);
});
