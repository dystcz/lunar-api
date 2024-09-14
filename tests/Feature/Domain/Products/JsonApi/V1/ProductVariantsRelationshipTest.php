<?php

use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can list product variants through relationship', function () {
    /** @var TestCase $this */

    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(ProductVariantFactory::new()->count(3), 'variants')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('product-variants')
        ->get(serverUrl("/products/{$product->getRouteKey()}/product-variants"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($product->variants)
        ->assertDoesntHaveIncluded();
})->group('products');

it('can count product variants', function () {
    /** @var TestCase $this */
    $product = Product::factory()
        ->has(
            ProductVariant::factory()->has(Price::factory())->count(5),
            'variants'
        )
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl("/products/{$product->getRouteKey()}?with-count=product-variants"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($product);

    expect($response->json('data.relationships.product-variants.meta.count'))->toBe(5);
})->group('products', 'counts');
