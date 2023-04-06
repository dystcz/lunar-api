<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Facades\CartSession;
use Lunar\Models\CartAddress;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->cart = Cart::factory()->create();

    $this->cartAddress = CartAddress::factory()->for($this->cart)->create();

    $this->data = [
        'id' => (string) $this->cartAddress->getRouteKey(),
        'type' => 'cart-addresses',
        'attributes' => [
            //
        ],
    ];
});

test('user can detach a shipping option', function () {
    CartSession::use($this->cart);

    $response = $this
        ->jsonApi()
        ->expects('cart-addresses')
        ->withData($this->data)
        ->delete('/api/v1/cart-addresses/'.$this->cartAddress->getRouteKey().'/-actions/detach-shipping-option');

    $response->assertFetchedOne($this->cartAddress);

    expect($this->cartAddress->fresh()->shipping_option)->toBeNull();
});

test('only the user who owns the cart address can detach shipping option for it', function () {
    $response = $this
        ->jsonApi()
        ->expects('cart-addresses')
        ->withData($this->data)
        ->delete('/api/v1/cart-addresses/'.$this->cartAddress->getRouteKey().'/-actions/detach-shipping-option');

    $response->assertErrorStatus([
        'detail' => 'This action is unauthorized.',
        'status' => '403',
        'title' => 'Forbidden',
    ]);
});
