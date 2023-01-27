<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Database\Factories\CartLineFactory;
use Lunar\Facades\CartSession;
use Lunar\Models\Currency;

uses(TestCase::class, RefreshDatabase::class);

it('can read the cart', function () {
    $currency = Currency::factory()->create();

    $cart = Cart::factory()
        ->has(
            CartLineFactory::new()->for(
                ProductVariantFactory::new()->has(
                    Price::factory()->state([
                        'price' => 100,
                        'tier' => 1,
                        'currency_id' => $currency->id,
                    ])
                ),
                'purchasable'
            ),
            'lines'
        )
        ->create(['currency_id' => $currency->id]);

    CartSession::use($cart);

    $response = $this
        ->jsonApi()
        ->expects('carts')
        ->includePaths('lines.purchasable.prices')
        ->get('/api/v1/carts/-actions/my-cart');

    $response->assertFetchedOne($cart);
});
