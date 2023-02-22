<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Facades\CartSession;

uses(TestCase::class, RefreshDatabase::class);

it('can read the cart', function () {
    $cart = Cart::factory()->withLines()->create();

    CartSession::use($cart);

    $response = $this
        ->jsonApi()
        ->expects('carts')
        ->includePaths(
            'lines.purchasable.prices',
            'lines.purchasable.product',
            'order'
        )
        ->get('/api/v1/carts/-actions/my-cart');

    $response->assertFetchedOne($cart);
});
