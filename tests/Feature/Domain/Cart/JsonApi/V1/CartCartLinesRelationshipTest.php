<?php

use Dystcz\LunarApi\Domain\CartLines\Models\CartLine;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->user = User::factory()
        ->has(Customer::factory())
        ->create();

    $this->cart = Cart::factory()
        ->for($this->user)
        ->withLines()
        ->create();

    /** @property CartSessionManager $cartSession */
    $this->cartSession = App::make(CartSessionInterface::class);

    $this->cartSession->use($this->cart);
});

it('can list related cart lines', function () {
    /** @var TestCase $this */
    $expected = $this->cart->lines->map(fn (CartLine $line) => [
        'type' => 'cart_lines',
        'id' => (string) $line->getRouteKey(),
        'attributes' => [
            'purchasable_id' => $line->purchasable_id,
            'purchasable_type' => $line->purchasable_type,
        ],
    ])->all();

    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->expects('cart_lines')
        ->get(serverUrl("/carts/{$this->cart->getRouteKey()}/cart_lines"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($expected);

})->group('carts', 'cart_lines');

it('can list related cart lines with purchasable product option values included', function () {
    /** @var TestCase $this */
    $expected = $this->cart->lines->map(fn (CartLine $line) => [
        'type' => 'cart_lines',
        'id' => (string) $line->getRouteKey(),
        'attributes' => [
            'purchasable_id' => $line->purchasable_id,
            'purchasable_type' => $line->purchasable_type,
        ],
    ])->all();

    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->expects('cart_lines')
        ->includePaths('purchasable.product_option_values')
        ->get(serverUrl("/carts/{$this->cart->getRouteKey()}/cart_lines"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($expected);

})->group('carts', 'cart_lines');

it('cannot list related cart lines without session and when not logged in', function () {
    /** @var TestCase $this */
    $this->cartSession->forget(delete: false);

    $response = $this
        ->jsonApi()
        ->expects('cart_lines')
        ->get(serverUrl("/carts/{$this->cart->getRouteKey()}/cart_lines"));

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);

})->group('carts', 'policies');

it('can list cart lines relationships when logged in', function () {
    /** @var TestCase $this */
    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->expects('cart_lines')
        ->get(serverUrl("/carts/{$this->cart->getRouteKey()}/relationships/cart_lines"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($this->cart->lines);

})->group('cart', 'cart_lines');

it('cannot list cart lines relationships without session and when not logged in', function () {
    /** @var TestCase $this */
    $this->cartSession->forget(delete: false);

    $response = $this
        ->jsonApi()
        ->expects('cart_lines')
        ->get(serverUrl("/carts/{$this->cart->getRouteKey()}/relationships/cart_lines"));

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);

})->group('carts', 'cart_lines', 'policies');
