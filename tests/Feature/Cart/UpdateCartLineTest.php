<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Database\Factories\CartLineFactory;
use Lunar\Models\Currency;

uses(TestCase::class, RefreshDatabase::class);

it('can update a cart line', function () {
    $currency = Currency::factory()->create();

    $cart = Cart::factory()->create(['currency_id' => $currency->id]);

    $cartLine = CartLineFactory::new()
        ->for(ProductVariantFactory::new(), 'purchasable')
        ->for($cart)
        ->create();

    \Lunar\Facades\CartSession::use($cart);

    $data = [
        'type' => 'cart-lines',
        'id' => (string) $cartLine->getRouteKey(),
        'attributes' => [
            'quantity' => 1,
        ],
    ];

    $response = $this
        ->jsonApi()
        ->expects('cart-lines')
        ->withData($data)
        ->patch('/api/v1/cart-lines/' . $cartLine->getRouteKey());

    $response->assertFetchedOne($cartLine);

    $this->assertDatabaseHas($cartLine->getTable(), [
        'id' => $cartLine->getKey(),
        'quantity' => $data['attributes']['quantity'],
    ]);
});

test('only the owner of the cart can update cart lines', function () {
    $cartLine = CartLineFactory::new()
        ->for(ProductVariantFactory::new(), 'purchasable')
        ->create();

    $data = [
        'type' => 'cart-lines',
        'id' => (string) $cartLine->getRouteKey(),
        'attributes' => [
            'quantity' => 1,
        ],
    ];

    $response = $this
        ->jsonApi()
        ->expects('cart-lines')
        ->withData($data)
        ->patch('/api/v1/cart-lines/' . $cartLine->getRouteKey());

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
});
