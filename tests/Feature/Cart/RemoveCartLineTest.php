<?php

use Dystcz\LunarApi\Domain\CartLines\Models\CartLine;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Facades\CartSession;
use Lunar\Models\Currency;

uses(TestCase::class, RefreshDatabase::class);

it('can remove a cart line', function () {
    /** @var TestCase $this */
    $currency = Currency::getDefault();

    $cart = Cart::factory()->create([
        'currency_id' => $currency->id,
    ]);

    $purchasable = ProductVariant::factory()->create();

    Price::factory()->create([
        'price' => 100,
        'tier' => 1,
        'currency_id' => $currency->id,
        'priceable_type' => $purchasable->getMorphClass(),
        'priceable_id' => $purchasable->id,
    ]);

    $cart->add($purchasable, 1);

    $this->assertCount(1, $cart->refresh()->lines);

    $cartLine = $cart->lines->first();

    CartSession::use($cart);

    $response = $this
        ->jsonApi()
        ->delete('/api/v1/cart-lines/'.$cartLine->getRouteKey());

    $response->assertNoContent();

    $this->assertDatabaseMissing($cartLine->getTable(), [
        'id' => $cartLine->getKey(),
    ]);
});

test('only the owner of the cart can delete cart lines', function () {
    /** @var TestCase $this */
    $cartLine = CartLine::factory()
        ->for(ProductVariantFactory::new(), 'purchasable')
        ->create();

    $response = $this
        ->jsonApi()
        ->delete('/api/v1/cart-lines/'.$cartLine->getRouteKey());

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
});
