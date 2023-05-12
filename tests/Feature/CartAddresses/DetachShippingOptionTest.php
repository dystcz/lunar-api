<?php

use Dystcz\LunarApi\Domain\Carts\Factories\CartAddressFactory;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Facades\CartSession;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->cart = Cart::factory()->create();

    $this->cartAddress = CartAddressFactory::new()->for($this->cart)->create();

    $this->data = [
        'id' => (string) $this->cartAddress->getRouteKey(),
        'type' => 'cart-addresses',
    ];
});

test('users can detach a shipping option from cart address', function () {
    CartSession::use($this->cart);

    $response = $this
        ->jsonApi()
        ->expects('cart-addresses')
        ->withData($this->data)
        ->delete('/api/v1/cart-addresses/'.$this->cartAddress->getRouteKey().'/-actions/detach-shipping-option');

    $response->assertFetchedOne($this->cartAddress);

    $this->assertDatabaseHas($this->cartAddress->getTable(), [
        'shipping_option' => null,
    ]);

    expect($this->cartAddress->fresh()->shipping_option)->toBeNull();
});

test('only the user who owns the cart address can detach shipping option for it', function () {
    $response = $this
        ->jsonApi()
        ->expects('cart-addresses')
        ->withData($this->data)
        ->delete('/api/v1/cart-addresses/'.$this->cartAddress->getRouteKey().'/-actions/detach-shipping-option');

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
});
