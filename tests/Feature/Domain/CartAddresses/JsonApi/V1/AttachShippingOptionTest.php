<?php

use Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Facades\CartSession;
use Lunar\Facades\ShippingManifest;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->cart = Cart::factory()->create();

    $this->cartAddress = CartAddress::factory()->for($this->cart)->create();

    $this->shippingOption = ShippingManifest::getOptions($this->cart)->first();

    $this->data = [
        'id' => (string) $this->cartAddress->getRouteKey(),
        'type' => 'cart-addresses',
        'attributes' => [
            'shipping_option' => $this->shippingOption->identifier,
        ],
    ];
});

test('users can attach a shipping option to cart address', function () {
    /** @var TestCase $this */
    CartSession::use($this->cart);

    $response = $this
        ->jsonApi()
        ->expects('cart-addresses')
        ->withData($this->data)
        ->patch('/api/v1/cart-addresses/'.$this->cartAddress->getRouteKey().'/-actions/attach-shipping-option');

    $response->assertFetchedOne($this->cartAddress);

    $this->assertDatabaseHas($this->cartAddress->getTable(), [
        'shipping_option' => $this->shippingOption->identifier,
    ]);

    expect($this->cartAddress->fresh()->shipping_option)->toBe($this->data['attributes']['shipping_option']);
});

test('only the user who owns the cart address can attach shipping option for it', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('cart-addresses')
        ->withData($this->data)
        ->patch('/api/v1/cart-addresses/'.$this->cartAddress->getRouteKey().'/-actions/attach-shipping-option');

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
});
