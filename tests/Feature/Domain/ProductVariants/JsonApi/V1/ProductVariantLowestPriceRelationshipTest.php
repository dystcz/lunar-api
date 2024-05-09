<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can read lowest price through relationship', function () {
    /** @var TestCase $this */
    $variant = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->withPrice()
        ->withPrice()
        ->withPrice()
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('prices')
        ->get(serverUrl("/variants/{$variant->getRouteKey()}/lowest_price"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant->prices->sortBy('price')->first())
        ->assertDoesntHaveIncluded();
})->group('product-variants');
