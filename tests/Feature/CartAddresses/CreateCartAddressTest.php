<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Facades\CartSession;
use Lunar\Models\CartAddress;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->cart = Cart::factory()->create();

    $this->cartAddress = CartAddress::factory()->make();

    $this->data = [
        'type' => 'cart-addresses',
        'attributes' => [
            'address_type' => $this->cartAddress->type,
            'first_name' => $this->cartAddress->first_name,
            'last_name' => $this->cartAddress->last_name,
            'company_name' => $this->cartAddress->company_name,
            'city' => $this->cartAddress->city,
            'line_one' => $this->cartAddress->line_one,
            'postcode' => $this->cartAddress->postcode,
        ],
        'relationships' => [
            'cart' => [
                'data' => [
                    'type' => 'carts',
                    'id' => (string) $this->cart->getRouteKey(),
                ],
            ],
        ],
    ];
});

it('can be created', function () {
    CartSession::use($this->cart);

    $response = $this
        ->jsonApi()
        ->expects('cart-addresses')
        ->withData($this->data)
        ->includePaths('cart')
        ->post('/api/v1/cart-addresses');

    $id = $response
        ->assertCreatedWithServerId('http://localhost/api/v1/cart-addresses', $this->data)
        ->id();

    $this->assertDatabaseHas($this->cartAddress->getTable(), [
        'id' => $id,
    ]);
});

test('only the user who owns the cart can assign an address to it', function () {
    $response = $this
        ->jsonApi()
        ->expects('cart-addresses')
        ->withData($this->data)
        ->includePaths('cart')
        ->post('/api/v1/cart-addresses');

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
});