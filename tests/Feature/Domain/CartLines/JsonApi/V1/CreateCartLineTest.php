<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
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
