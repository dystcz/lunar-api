<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Lunar\Facades\CartSession;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Country;

uses(TestCase::class, RefreshDatabase::class);

it('can fetch shipping options for cart', function () {
    /** @var TestCase $this */
    $cart = Cart::factory()->create();

    CartSession::use($cart);

    $response = $this
        ->jsonApi()
        ->expects('shipping-options')
        ->get('/api/v1/shipping-options');

    $response->assertSuccessful();

    $shippingOption = ShippingManifest::getOptions($cart)->firstWhere('name', 'Basic Delivery');

    $response->assertFetchedMany([
        [
            'type' => 'shipping-options',
            'id' => 'ffcdel',
            'attributes' => [
                'name' => 'Basic Delivery',
                'description' => 'A basic delivery option',
                'identifier' => 'FFCDEL',
                'price' => [
                    'decimal' => (int) $shippingOption->price->decimal,
                    'formatted' => $shippingOption->price->formatted,
                ],
                'currency' => $shippingOption->price->currency->toArray(),
            ],
        ],
    ]);
});

it('can fetch shipping options for a cart based on country', function () {
    /** @var TestCase $this */
    $country = Country::factory()->create([
        'name' => 'Czech Republic',
        'iso3' => 'CZE',
        'iso2' => 'CZ',
    ]);

    $cart = Cart::withoutEvents(function () use ($country) {
        return Cart::factory()
            ->withAddresses(['country_id' => $country->id])
            ->create();
    });

    CartSession::use($cart);

    $response = $this
        ->jsonApi()
        ->expects('shipping-options')
        ->get('http://localhost/api/v1/shipping-options');

    $response->assertSuccessful();

    $shippingOptions = ShippingManifest::getOptions($cart);

    $response->assertFetchedMany([
        [
            'type' => 'shipping-options',
            'id' => (string) Str::slug($shippingOptions[0]->getIdentifier()),
            'attributes' => [
                'name' => $shippingOptions[0]->getName(),
                'description' => $shippingOptions[0]->getDescription(),
                'identifier' => $shippingOptions[0]->getIdentifier(),
            ],
        ],
        [
            'type' => 'shipping-options',
            'id' => (string) Str::slug($shippingOptions[1]->getIdentifier()),
            'attributes' => [
                'name' => $shippingOptions[1]->getName(),
                'description' => $shippingOptions[1]->getDescription(),
                'identifier' => $shippingOptions[1]->getIdentifier(),
            ],
        ],
    ]);
});
