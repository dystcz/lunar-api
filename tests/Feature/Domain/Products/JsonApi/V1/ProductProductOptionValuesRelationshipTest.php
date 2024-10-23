<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\ProductOption;
use Lunar\Models\ProductOptionValue;

uses(TestCase::class, RefreshDatabase::class);

it('can list product options values through relationship', function () {
    /** @var TestCase $this */
    $product = Product::factory()
        ->hasAttached(
            ProductOption::factory()
                ->count(2)
                ->has(ProductOptionValue::factory()->count(3), 'values'),
            ['position' => 1],
        )
        ->create();

    $values = $product->productOptions->map(fn (ProductOption $option) => $option->values->first());

    $variant = ProductVariant::factory()
        ->for($product, 'product')
        ->hasAttached($values, [], 'values')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('product_option_values')
        ->get(serverUrl("/products/{$product->getRouteKey()}/product_option_values"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($product->variantValues)
        ->assertDoesntHaveIncluded();
})->group('products');

it('can count product option values', function () {
    /** @var TestCase $this */
    $product = Product::factory()
        ->hasAttached(
            ProductOption::factory()
                ->count(2)
                ->has(ProductOptionValue::factory()->count(3), 'values'),
            ['position' => 1],
        )
        ->create();

    $values = $product->productOptions->map(fn (ProductOption $option) => $option->values->first());

    $variant = ProductVariant::factory()
        ->for($product, 'product')
        ->hasAttached($values, [], 'values')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl("/products/{$product->getRouteKey()}?with_count=product_option_values"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($product);

    expect($response->json('data.relationships.product_option_values.meta.count'))->toBe(2);
})->group('products', 'counts');
