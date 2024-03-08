<?php

use Dystcz\LunarApi\Domain\CartLines\Models\CartLine;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\LunarApi;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can add purchasable to the cart', function () {
    /** @var TestCase $this */
    $cart = Cart::factory()->withLines()->create();

    $cartLine = $cart->lines->first();

    $data = [
        'type' => 'cart-lines',
        'attributes' => [
            'quantity' => $cartLine->quantity,
            'purchasable_id' => $cartLine->purchasable_id,
            'purchasable_type' => $cartLine->purchasable_type,
            'meta' => null,
        ],
    ];

    $response = $this
        ->jsonApi()
        ->expects('cart-lines')
        ->withData($data)
        ->post(serverUrl('/cart-lines'));

    $id = $response
        ->assertCreatedWithServerId('http://localhost/api/v1/cart-lines', $data)
        ->id();

    if (LunarApi::usesHashids()) {
        $id = decodeHashedId($cartLine, $id);
    }

    $this->assertDatabaseHas($cartLine->getTable(), [
        'id' => $id,
        'purchasable_id' => $cartLine->purchasable_id,
        'purchasable_type' => $cartLine->purchasable_type,
        'quantity' => $cartLine->quantity,
    ]);
})->group('cart-lines');

it('can update a cart line', function () {
    /** @var TestCase $this */
    $cart = Cart::factory()->create();

    $cartLine = CartLine::factory()
        ->for(
            ProductVariantFactory::new()->for(ProductFactory::new())->withPrice(),
            'purchasable'
        )
        ->for($cart)
        ->create();

    CartSession::use($cart);

    $data = [
        'type' => 'cart-lines',
        'id' => (string) $cartLine->getRouteKey(),
        'attributes' => [
            'purchasable_id' => $cartLine->purchasable_id,
            'purchasable_type' => $cartLine->purchasable_type,
            'quantity' => 1,
        ],
    ];

    $response = $this
        ->jsonApi()
        ->expects('cart-lines')
        ->withData($data)
        ->patch('/api/v1/cart-lines/'.$cartLine->getRouteKey());

    $response->assertFetchedOne($cartLine);

    $this->assertDatabaseHas($cartLine->getTable(), [
        'id' => $cartLine->getKey(),
        'quantity' => $data['attributes']['quantity'],
    ]);
})->group('cart-lines');

test('only the owner of the cart can update cart lines', function () {
    /** @var TestCase $this */
    $cartLine = CartLine::factory()
        ->for(ProductVariantFactory::new(), 'purchasable')
        ->create();

    $data = [
        'type' => 'cart-lines',
        'id' => (string) $cartLine->getRouteKey(),
        'attributes' => [
            'purchasable_id' => $cartLine->purchasable_id,
            'purchasable_type' => $cartLine->purchasable_type,
            'quantity' => 1,
        ],
    ];

    $response = $this
        ->jsonApi()
        ->expects('cart-lines')
        ->withData($data)
        ->patch('/api/v1/cart-lines/'.$cartLine->getRouteKey());

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
})->group('cart-lines', 'policies');
