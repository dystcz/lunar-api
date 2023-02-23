<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Facades\CartSession;

uses(TestCase::class, RefreshDatabase::class);

it('can read the cart', function () {
    $cart = Cart::factory()->withLines()->withAddresses()->create();

    CartSession::use($cart);

    $response = $this
        ->jsonApi()
        ->expects('carts')
        ->includePaths(
            'lines.purchasable.prices',
            'lines.purchasable.product',
            'order',
            'addresses',
            'shippingAddress',
            'billingAddress',
        )
        ->get('/api/v1/carts/-actions/my-cart');

    $response->assertFetchedOne($cart)
            ->assertIsIncluded('cart-addresses', $cart->shippingAddress)
            ->assertIsIncluded('cart-addresses', $cart->billingAddress);
});
