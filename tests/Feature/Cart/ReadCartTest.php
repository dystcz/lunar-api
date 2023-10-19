<?php

use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Facades\CartSession;

uses(TestCase::class, RefreshDatabase::class);

it('can read the cart', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();

    $cart = CartFactory::new()
        ->for($user)
        ->withLines()
        ->create();

    CartSession::use($cart);

    $response = $this
        ->actingAs($user)
        ->jsonApi()
        ->expects('carts')
        ->includePaths(
            'cart_lines.purchasable.prices',
            'cart_lines.purchasable.product',
            'order',
            'cart_addresses',
            'shipping_address',
            'billing_address',
        )
        ->get('/api/v1/carts/-actions/my-cart');

    $response->assertFetchedOne($cart)
        ->assertIsIncluded('cart-addresses', $cart->shippingAddress)
        ->assertIsIncluded('cart-addresses', $cart->billingAddress);
});
