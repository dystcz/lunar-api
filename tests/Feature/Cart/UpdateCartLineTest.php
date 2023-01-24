<?php

use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Database\Factories\CartLineFactory;

uses(TestCase::class, RefreshDatabase::class);

it('can update a cart line', function () {
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
        ->patch('/api/v1/cart-lines/'.$cartLine->getRouteKey());

    $response->assertFetchedOne($cartLine);

    $this->assertDatabaseHas($cartLine->getTable(), [
        'id' => $cartLine->getKey(),
        'quantity' => $data['attributes']['quantity'],
    ]);
});
