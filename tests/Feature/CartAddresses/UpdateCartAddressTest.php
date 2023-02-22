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
            'address_type' => $this->cartAddress->type,
            'first_name' => $this->cartAddress->first_name,
            'last_name' => $this->cartAddress->last_name,
            'company_name' => $this->cartAddress->company_name,
            'city' => $this->cartAddress->city,
            'line_one' => $this->cartAddress->line_one,
            'postcode' => $this->cartAddress->postcode,
        ]
    ];
});

test('a cart address can be update', function () {
    CartSession::use($this->cart);

    $response = $this
        ->jsonApi()
        ->expects('cart-addresses')
        ->withData($this->data)
        ->patch('/api/v1/cart-addresses/' . $this->cartAddress->getRouteKey());

    $response->assertFetchedOne($this->cartAddress);
});

test('only the user who owns the cart can assign address to it', function () {
    $response = $this
        ->jsonApi()
        ->expects('cart-addresses')
        ->withData($this->data)
        ->patch('/api/v1/cart-addresses/' . $this->cartAddress->getRouteKey());

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
});