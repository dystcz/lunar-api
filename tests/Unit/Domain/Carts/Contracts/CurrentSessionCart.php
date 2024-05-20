<?php

use Dystcz\LunarApi\Domain\Carts\Contracts\CurrentSessionCart;
use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Base\CartSessionInterface;

uses(TestCase::class, RefreshDatabase::class);

it('can get current cart from session when resolving contract', function () {
    /** @var Cart $cart */
    $carts = CartFactory::new()
        ->withAddresses()
        ->withLines()
        ->count(3)
        ->create();

    $sessionCart = array_rand($carts);

    /** @var \Lunar\Managers\CartSessionManager $cartSession */
    $cartSession = App::make(CartSessionInterface::class);

    $cartSession->use($sessionCart);

    /** @var TestCase $this */
    $cart = App::make(CurrentSessionCart::class);

    $this->assertInstanceOf(Cart::class, $sessionCart);

})->group('carts', 'carts.model');
