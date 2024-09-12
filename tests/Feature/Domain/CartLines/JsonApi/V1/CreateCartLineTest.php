<?php

use Dystcz\LunarApi\Domain\CartLines\Models\CartLine;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApi\LunarApi;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Lunar\Base\CartSessionInterface;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->cartSession = App::make(CartSessionInterface::class);
});

it('can add purchasable to a cart which does not yet exist', function () {
    /** @var TestCase $this */
    Config::set('lunar.cart.auto_create', true);

    /** @var ProductVariant $purchasable */
    $purchasable = ProductVariant::factory()
        ->for(Product::factory())
        ->withPrice()
        ->create();

    $data = [
        'type' => 'cart-lines',
        'attributes' => [
            'quantity' => 1,
            'purchasable_id' => (int) $purchasable->getRouteKey(),
            'purchasable_type' => $purchasable->getMorphClass(),
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
        ->assertCreatedWithServerId(serverUrl('/cart-lines', true), $data)
        ->id();

    $cartLine = CartLine::query()
        ->where('id', $id)
        ->first();

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

it('can associate existing cart to users after they log in', function () {
    /** @var TestCase $this */
    Config::set('lunar.cart.auto_create', true);

    $cart = Cart::factory()->create();
    $user = User::factory()->has(Customer::factory())->create();

    /** @var ProductVariant $purchasable */
    $purchasable = ProductVariant::factory()
        ->for(Product::factory())
        ->withPrice()
        ->create();

    $this->assertEmpty($cart->lines);
    $this->assertNull($cart->user);

    $this->cartSession->use($cart);

    $data = [
        'type' => 'cart-lines',
        'attributes' => [
            'quantity' => 1,
            'purchasable_id' => (int) $purchasable->getRouteKey(),
            'purchasable_type' => $purchasable->getMorphClass(),
            'meta' => null,
        ],
    ];

    Auth::guard('web')->login($user);

    $cart = $cart->fresh();

    $this->assertSame($cart->user->getKey(), $user->getKey());

    if (LunarApi::usesHashids()) {
        $cartId = decodeHashedId($cart, $cart->id);
        $userId = decodeHashedId($cart, $cart->user->id);
    }

    $this->assertDatabaseHas($cart->getTable(), [
        'id' => $cartId ?? $cart->id,
        'user_id' => $userId ?? $cart->user->id,
    ]);
})->group('cart-lines');

it('can add purchasable to an existing cart', function () {
    /** @var TestCase $this */
    Config::set('lunar.cart.auto_create', true);

    $cart = Cart::factory()->create();

    /** @var ProductVariant $purchasable */
    $purchasable = ProductVariant::factory()
        ->for(Product::factory())
        ->withPrice()
        ->create();

    $this->assertEmpty($cart->lines);

    $data = [
        'type' => 'cart-lines',
        'attributes' => [
            'quantity' => 1,
            'purchasable_id' => (int) $purchasable->getRouteKey(),
            'purchasable_type' => $purchasable->getMorphClass(),
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
        ->assertCreatedWithServerId(serverUrl('/cart-lines', true), $data)
        ->id();

    $cartLine = CartLine::query()
        ->where('id', $id)
        ->first();

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

    $this->assertNull($this->cartSession->current());

    // This adds cart to session
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
