<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can read product through relationship', function () {
    /** @var TestCase $this */
    $variants = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->count(2)
        ->create();

    $variant = $variants->first();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl("/variants/{$variant->getRouteKey()}/product"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant->product)
        ->assertDoesntHaveIncluded();
})->group('variants');
