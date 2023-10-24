<?php

use Dystcz\LunarApi\Domain\CartLines\Models\CartLine;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Facades\CartSession;

uses(TestCase::class, RefreshDatabase::class);

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
});

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
});
