<?php

use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

test('product can include prices', function () {
    /** @var TestCase $this */

    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->withPrice(),
            'variants'
        )
        ->create();

    $self = 'http://localhost/api/v1/products/'.$product->getRouteKey();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->includePaths('prices')
        ->get($self);

    $response
        ->assertFetchedOne($product)
        ->assertIsIncluded('prices', $product->prices->first());

})->group('products', 'prices');

test('product can include lowest price', function () {
    /** @var TestCase $this */

    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->withPrice(),
            'variants'
        )
        ->create();

    $self = 'http://localhost/api/v1/products/'.$product->getRouteKey();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->includePaths(
            'lowest_price',
        )
        ->get($self);

    $response->assertFetchedOne($product)
        ->assertIsIncluded('prices', $product->lowestPrice);
})->group('products', 'prices');
