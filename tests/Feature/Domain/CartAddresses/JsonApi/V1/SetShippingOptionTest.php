<?php

use Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;
use Lunar\Facades\ShippingManifest;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->cartSession = App::make(CartSessionInterface::class);

    $this->cart = Cart::factory()->create();

    $this->cartAddress = CartAddress::factory()->for($this->cart)->create();

    $this->shippingOption = ShippingManifest::getOptions($this->cart)->first();

    $this->data = [
        'id' => (string) $this->cartAddress->getRouteKey(),
        'type' => 'cart_addresses',
        'attributes' => [
            'shipping_option' => $this->shippingOption->identifier,
        ],
    ];
});

test('users can set a shipping option to cart address', function () {
    /** @var TestCase $this */
    $this->cartSession->use($this->cart);

    $response = $this
        ->jsonApi()
        ->expects('cart_addresses')
        ->withData($this->data)
        ->patch(serverUrl("/cart_addresses/{$this->cartAddress->getRouteKey()}/-actions/set-shipping-option"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($this->cartAddress);

    $this->assertDatabaseHas($this->cartAddress->getTable(), [
        'shipping_option' => $this->shippingOption->identifier,
    ]);

    expect($this->cartAddress->fresh()->shipping_option)->toBe($this->data['attributes']['shipping_option']);
})->group('cart-addresses', 'shipping-options');

it('validates shipping option attribute when setting shipping option to cart address', function () {
    /** @var TestCase $this */
    $this->cartSession->use($this->cart);

    $response = $this
        ->jsonApi()
        ->expects('cart_addresses')
        ->withData([
            'id' => (string) $this->cartAddress->getRouteKey(),
            'type' => 'cart_addresses',
            'attributes' => [
                'shipping_option' => null,
            ],
        ])
        ->patch(serverUrl("/cart_addresses/{$this->cartAddress->getRouteKey()}/-actions/set-shipping-option"));

    $response->assertErrorStatus([
        'detail' => __('lunar-api::validations.shipping.set_shipping_option.shipping_option.required'),
        'status' => '422',
    ]);
})->group('cart-addresses', 'shipping-options');

test('only the user who owns the cart address can set shipping option for it', function () {
    /** @var TestCase $this */
    $this->cartSession->forget();

    $response = $this
        ->jsonApi()
        ->expects('cart_addresses')
        ->withData($this->data)
        ->patch(serverUrl("/cart_addresses/{$this->cartAddress->getRouteKey()}/-actions/set-shipping-option"));

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
})->group('cart-addresses', 'shipping-options');
