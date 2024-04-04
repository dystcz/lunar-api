<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\LunarApi;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Lunar\Base\CartSessionInterface;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->cartSession = App::make(CartSessionInterface::class);
});

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
        ->assertSuccessful()
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

it('manually creates a cart when cart auto create turned off', function () {
    /** @var TestCase $this */
    Config::set('lunar.cart.auto_create', false);

    $cart = Cart::factory()
        ->withLines()
        ->create();

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
        ->expects('carts')
        ->get(serverUrl('/carts/-actions/my-cart'));

    // No cart in session
    $response
        ->assertSuccessful()
        ->assertFetchedNull();

    $response = $this
        ->jsonApi()
        ->expects('cart-lines')
        ->withData($data)
        ->post(serverUrl('/cart-lines'));

    $id = $response
        ->assertSuccessful()
        ->assertCreatedWithServerId(serverUrl('/cart-lines', true), $data)
        ->id();

    $response = $this
        ->jsonApi()
        ->expects('carts')
        ->get(serverUrl('/carts/-actions/my-cart'));

    $cart = $this->cartSession->current();

    $response
        ->assertSuccessful()
        ->assertFetchedOne($cart);

    if (LunarApi::usesHashids()) {
        $id = decodeHashedId($cartLine, $id);
    }

    $this->assertDatabaseHas($cartLine->getTable(), [
        'id' => $id,
        'cart_id' => $cart->id,
        'purchasable_id' => $cartLine->purchasable_id,
        'purchasable_type' => $cartLine->purchasable_type,
        'quantity' => $cartLine->quantity,
    ]);

})->group('cart-lines', 'carts');
