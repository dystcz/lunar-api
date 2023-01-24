<?php

use Dystcz\LunarApi\Domain\Carts\Actions\AddToCart;
use Dystcz\LunarApi\Domain\Carts\Data\CartLineData;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Database\Factories\CartLineFactory;

uses(TestCase::class, RefreshDatabase::class);

it('can add purchsable to the cart', function () {
    $cartLine = CartLineFactory::new()
        ->for(ProductVariantFactory::new()->create(), 'purchasable')
        ->make();

    $data = [
        'type' => 'cart-lines',
        'attributes' => [
            'quantity' => $cartLine->quantity,
            'purchasable_id' => $cartLine->purchasable_id,
            'purchasable_type' => $cartLine->purchasable_type,
            'meta' => $cartLine->meta,
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
        'meta' => $cartLine->meta,
    ]);
});

test('cart line quantity will be incremented if already presented inside the user\'s cart', function () {
    $productVariant = ProductVariantFactory::new()->create();

    $cartLine = CartLineFactory::new()
        ->for($productVariant, 'purchasable')
        ->make();

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
            'meta' => $cartLine->meta,
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
