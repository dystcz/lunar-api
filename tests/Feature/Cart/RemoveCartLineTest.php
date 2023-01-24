<?php

use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Database\Factories\CartLineFactory;

uses(TestCase::class, RefreshDatabase::class);

it('cat remove a cart line', function () {
    $cartLine = CartLineFactory::new()
        ->for(ProductVariantFactory::new(), 'purchasable')
        ->create();

    $response = $this
        ->jsonApi()
        ->delete('/api/v1/cart-lines/'.$cartLine->getRouteKey());

    $response->assertNoContent();

    $this->assertDatabaseMissing($cartLine->getTable(), [
        'id' => $cartLine->getKey(),
    ]);
});
