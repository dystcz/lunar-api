<?php

use Dystcz\LunarApi\Domain\Carts\Factories\CartLineFactory;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Facades\CartSession;
use Lunar\Models\Currency;

uses(TestCase::class, RefreshDatabase::class);

it('can remove a cart line', function () {
    $currency = Currency::factory()->create();

    $cart = Cart::factory()->create(['currency_id' => $currency->id]);

    $cartLine = CartLineFactory::new()
        ->for(ProductVariantFactory::new(), 'purchasable')
        ->for($cart)
        ->create();

    CartSession::use($cart);

    $response = $this
        ->jsonApi()
        ->delete('/api/v1/cart-lines/' . $cartLine->getRouteKey());

    $response->assertNoContent();

    $this->assertDatabaseMissing($cartLine->getTable(), [
        'id' => $cartLine->getKey(),
    ]);
});

test('only the owner of the cart can delete cart lines', function () {
    $cartLine = CartLineFactory::new()
        ->for(ProductVariantFactory::new(), 'purchasable')
        ->create();

    $response = $this
        ->jsonApi()
        ->delete('/api/v1/cart-lines/' . $cartLine->getRouteKey());

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
});
