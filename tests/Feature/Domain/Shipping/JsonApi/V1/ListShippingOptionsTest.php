<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Lunar\Base\CartSessionInterface;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Country;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->cart = Cart::factory()->create();

    /** @property CartSessionManager $cartSession */
    $this->cartSession = App::make(CartSessionInterface::class);

    $this->cartSession->use($this->cart);
});

it('can list shipping options for a cart', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('shipping-options')
        ->get(serverUrl('/shipping-options'));

    $response->assertSuccessful();

    $shippingOption = ShippingManifest::getOptions($this->cart)->firstWhere('name', 'Basic Delivery');

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
                'currency' => Arr::only($shippingOption->price->currency->toArray(), ['code', 'name']),
            ],
        ],
    ]);
})->group('shipping-options');

it('can list shipping options for a cart based on country', function () {
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

    $this->cartSession->use($cart);

    $response = $this
        ->jsonApi()
        ->expects('shipping-options')
        ->get(serverUrl('/shipping-options'));

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
})->group('shipping-options');
