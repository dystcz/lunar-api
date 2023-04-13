<?php

namespace Dystcz\LunarApi\Tests\Feature\CartAddresses;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Facades\CartSession;
use Lunar\Models\CartAddress;
use Lunar\Models\Country;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->cart = Cart::factory()->create();

    $this->cartAddress = CartAddress::factory()->for($this->cart)->create();

    $this->country = Country::factory()->create();

    $this->data = [
        'id' => (string) $this->cartAddress->getRouteKey(),
        'type' => 'cart-addresses',
        'relationships' => [
            'country' => [
                'data' => [
                    'type' => 'countries',
                    'id' => (string) $this->country->getRouteKey(),
                ],
            ],
        ],
    ];
});

test('users can update cart address country', function () {
    CartSession::use($this->cart);

    $response = $this
        ->jsonApi()
        ->expects('cart-addresses')
        ->withData($this->data)
        ->patch('/api/v1/cart-addresses/'.$this->cartAddress->getRouteKey().'/-actions/update-country');

    $response->assertFetchedOne($this->cartAddress);

    $this->assertDatabaseHas($this->cartAddress->getTable(), [
        'country_id' => $this->country->id,
    ]);
});

test('only the user who owns the cart address can update its country', function () {
    $response = $this
        ->jsonApi()
        ->expects('cart-addresses')
        ->withData($this->data)
        ->patch('/api/v1/cart-addresses/'.$this->cartAddress->getRouteKey().'/-actions/update-country');

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
});
