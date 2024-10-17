<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\ProductOption;

uses(TestCase::class, RefreshDatabase::class);

it('can list product options through relationship', function () {
    /** @var TestCase $this */
    $product = Product::factory()
        ->hasAttached(ProductOption::factory()->count(2), ['position' => 1])
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('product_options')
        ->get(serverUrl("/products/{$product->getRouteKey()}/product_options"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($product->productOptions)
        ->assertDoesntHaveIncluded();
})->group('products');

it('can count product options', function () {
    /** @var TestCase $this */
    $product = Product::factory()
        ->hasAttached(ProductOption::factory()->count(4), ['position' => 1])
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl("/products/{$product->getRouteKey()}?with_count=product_options"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($product);

    expect($response->json('data.relationships.product_options.meta.count'))->toBe(4);
})->group('products', 'counts');
