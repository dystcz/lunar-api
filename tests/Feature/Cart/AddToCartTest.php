<?php

namespace Dystcz\LunarApi\Tests\Feature\Cart;

use Dystcz\LunarApi\Domain\Carts\Actions\AddToCart;
use Dystcz\LunarApi\Domain\Carts\Data\CartLineData;
use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;

uses(TestCase::class, RefreshDatabase::class);

it('can add purchasable to the cart', function () {
    $cart = CartFactory::new()->withLines()->create();

    $cartLine = $cart->lines->first();

    $data = [
        'type' => 'cart-lines',
        'attributes' => [
            'quantity' => $cartLine->quantity,
            'purchasable_id' => $cartLine->purchasable_id,
            'purchasable_type' => $cartLine->purchasable_type,
            'meta' => [],
        ],
    ];

    $response = $this
        ->jsonApi()
        ->expects('cart-lines')
        ->withData($data)
        ->post('/api/v1/cart-lines');

    $id = $response
        ->assertCreatedWithServerId('http://localhost/api/v1/cart-lines', $data)
        ->id();

    $this->assertDatabaseHas($cartLine->getTable(), [
        'id' => $id,
        'purchasable_id' => $cartLine->purchasable_id,
        'purchasable_type' => $cartLine->purchasable_type,
        'quantity' => $cartLine->quantity,
    ]);
});

test('cart line quantity will be incremented if already presented inside the user\'s cart', function () {
    $cart = Cart::factory()->withLines()->create();

    $cartLine = $cart->lines->first();

    App::make(AddToCart::class)(
        new CartLineData(
            purchasable_type: $cartLine->purchasable_type,
            purchasable_id: $cartLine->purchasable_id,
            quantity: $cartLine->quantity,
            meta: $cartLine->meta,
        )
    );

    $data = [
        'type' => 'cart-lines',
        'attributes' => [
            'quantity' => $cartLine->quantity,
            'purchasable_id' => $cartLine->purchasable_id,
            'purchasable_type' => $cartLine->purchasable_type,
            'meta' => [],
        ],
    ];

    $response = $this
        ->jsonApi()
        ->expects('cart-lines')
        ->withData($data)
        ->post('/api/v1/cart-lines');

    $data['attributes']['quantity'] = $cartLine->quantity * 2;

    $id = $response
        ->assertCreatedWithServerId('http://localhost/api/v1/cart-lines', $data)
        ->id();

    $this->assertDatabaseHas($cartLine->getTable(), [
        'id' => $id,
        'quantity' => $cartLine->quantity * 2,
    ]);
});
