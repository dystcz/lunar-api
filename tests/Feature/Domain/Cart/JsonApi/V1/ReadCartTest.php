<?php

use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Lunar\Base\CartSessionInterface;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->cartSession = App::make(CartSessionInterface::class);
});

afterEach(function () {
    /** @var TestCase $this */
    $this->cartSession->forget();
});

it('can read the cart from session', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();

    $cart = CartFactory::new()
        ->for($user)
        ->withLines()
        ->create();

    $this->cartSession->use($cart);

    $response = $this
        ->actingAs($user)
        ->jsonApi()
        ->expects('carts')
        ->get('/api/v1/carts/-actions/my-cart');

    $response
        ->assertSuccessful()
        ->assertFetchedOne($cart);

})->group('carts', 'carts.read');

it('can read cart with cart lines included', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();

    $cart = CartFactory::new()
        ->for($user)
        ->withLines()
        ->create();

    $this->cartSession->use($cart);

    $response = $this
        ->actingAs($user)
        ->jsonApi()
        ->expects('carts')
        ->includePaths(
            'cart_lines.purchasable.prices',
            'cart_lines.purchasable.product.thumbnail',
            'cart_lines.purchasable.product_option_values',
            'cart_lines.purchasable.product_option_values.product_option',
        )
        ->get(serverUrl("/carts/{$cart->getRouteKey()}"));

    $response
        ->assertSuccessful();

})->group('carts');

it('can merge carts when user logs in', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();

    $userCart = CartFactory::new()
        ->for($user)
        ->withLines(2)
        ->create();

    $userCartLines = $userCart->lines->map(fn ($line) => [
        'type' => 'cart_lines',
        'id' => (string) $line->getRouteKey(),
        'attributes' => [
            'purchasable_type' => $line->purchasable_type,
            'purchasable_id' => $line->purchasable_id,
        ],
    ]);

    $sessionCart = CartFactory::new()
        ->withLines(3)
        ->create();

    $sessionCartLines = $sessionCart->lines->map(fn ($line) => [
        'type' => 'cart_lines',
        'id' => (string) $line->getRouteKey(),
        'attributes' => [
            'purchasable_type' => $line->purchasable_type,
            'purchasable_id' => $line->purchasable_id,
        ],
    ]);

    $this->cartSession->use($sessionCart);

    $response = $this
        ->jsonApi()
        ->expects('carts')
        ->includePaths('cart_lines')
        ->get(serverUrl('/carts/-actions/my-cart'));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($sessionCart)
        ->assertIncluded([...$sessionCartLines]);

    $this->assertDatabaseHas($sessionCart->getTable(), [
        'id' => $sessionCart->id,
        'user_id' => null,
    ]);

    foreach ($sessionCartLines as $line) {
        $this->assertDatabaseHas($sessionCart->lines->first()->getTable(), [
            'id' => $line['id'],
            'cart_id' => $sessionCart->id,
            'purchasable_type' => $line['attributes']['purchasable_type'],
            'purchasable_id' => $line['attributes']['purchasable_id'],
        ]);
    }

    Event::dispatch(new Login('web', $user, true));

    $response = $this
        ->actingAs($user)
        ->jsonApi()
        ->expects('carts')
        ->includePaths('cart_lines')
        ->get(serverUrl('/carts/-actions/my-cart'));

    $mergedUserCartLines = $this->cartSession->current()->lines->map(fn ($line) => [
        'type' => 'cart_lines',
        'id' => (string) $line->getRouteKey(),
        'attributes' => [
            'purchasable_type' => $line->purchasable_type,
            'purchasable_id' => $line->purchasable_id,
        ],
    ]);

    $response
        ->assertSuccessful()
        ->assertFetchedOne($userCart)
        ->assertIncluded([...$mergedUserCartLines]);

    foreach ($userCartLines as $line) {
        $this->assertDatabaseHas($sessionCart->lines->first()->getTable(), [
            'cart_id' => $userCart->id,
            'purchasable_type' => $line['attributes']['purchasable_type'],
            'purchasable_id' => $line['attributes']['purchasable_id'],
        ]);
    }

    foreach ($sessionCartLines as $line) {
        $this->assertDatabaseHas($sessionCart->lines->first()->getTable(), [
            'cart_id' => $userCart->id,
            'purchasable_type' => $line['attributes']['purchasable_type'],
            'purchasable_id' => $line['attributes']['purchasable_id'],
        ]);
    }
})->group('carts', 'carts.read')->skip('Merging carts is temporarily disabled.');
