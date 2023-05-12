<?php

use Dystcz\LunarApi\Domain\Carts\Factories\CartAddressFactory;
use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Facades\CartSession;
use Lunar\Facades\ShippingManifest;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->cart = CartFactory::new()->create();

    $this->cartAddress = CartAddressFactory::new()->for($this->cart)->create();

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
