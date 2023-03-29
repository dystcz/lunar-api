<?php

use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can read order details', function () {
    $response = $this
        ->jsonApi()
        ->expects('shipping-options')
        ->get('http://localhost/api/v1/shipping-options');

    $response->assertSuccessful();

    $shippingOption = \Lunar\Facades\ShippingManifest::getOptions(new \Lunar\Models\Cart)->first();

    expect($response->json('data')[0])->toBe([
        'type' => 'shipping-options',
        'id' => 'friendly-freight-co',
        'attributes' => [
            'name' => 'Basic Delivery',
            'description' => 'A basic delivery option',
            'identifier' => 'Friendly Freight Co.',
            'price' => [
                'decimal' => (int) $shippingOption->price->decimal,
                'formatted' => $shippingOption->price->formatted,
            ],
            'currency' => $shippingOption->price->currency->toArray(),
        ],
        'links' => [
            'self' => 'http://localhost/api/v1/shipping-options/friendly-freight-co',
        ],
    ]);
});
