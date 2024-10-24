<?php

use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can show product variant detail', function () {
    /** @var TestCase $this */
    $variant = ProductVariant::factory()->create();

    $response = $this
        ->jsonApi()
        ->expects('product_variants')
        ->get(serverUrl('/product_variants/'.$variant->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant)
        ->assertDoesntHaveIncluded();
})->group('product_variants');

it('returns error response when product variant does not exists', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('product_variants')
        ->get(serverUrl('/product_variants/1'));

    $response
        ->assertErrorStatus([
            'status' => '404',
            'title' => 'Not Found',
        ]);
})->group('variants', 'policies');

test('can show a product variant with included images', function () {
    /** @var TestCase $this */

    /** @var ProductVariant $variant */
    $variant = ProductVariantFactory::new()
        ->withImages(3)
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('product_variants')
        ->includePaths('images')
        ->get(serverUrl('/product_variants/'.$variant->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant)
        ->assertIsIncluded('media', $variant->images->first());
})->group('product_variants');

it('can show a product variant with included thumbnail', function () {
    /** @var TestCase $this */

    /** @var ProductVariant $variant */
    $variant = ProductVariantFactory::new()
        ->withImages(3)
        ->withThumbnail()
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('product_variants')
        ->includePaths('thumbnail')
        ->get(serverUrl('/product_variants/'.$variant->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant)
        ->assertIsIncluded('media', $variant->thumbnail);
})->group('product_variants');

it('can show a product variant with included product', function () {
    /** @var TestCase $this */
    $variant = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('product_variants')
        ->includePaths('product')
        ->get(serverUrl('/product_variants/'.$variant->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant)
        ->assertIsIncluded('products', $variant->product);

})->group('product_variants');

it('can show a product variant with included lowest price', function () {
    /** @var TestCase $this */
    $variant = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->has(Price::factory()->count(5), 'prices')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('product_variants')
        ->includePaths('lowest_price')
        ->get(serverUrl('/product_variants/'.$variant->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant)
        ->assertIsIncluded('prices', $variant->prices->sortBy('price')->first());

})->group('product_variants');

it('can show a product variant with included highest price', function () {
    /** @var TestCase $this */
    $variant = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->has(Price::factory()->count(5), 'prices')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('product_variants')
        ->includePaths('highest_price')
        ->get(serverUrl('/product_variants/'.$variant->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant)
        ->assertIsIncluded('prices', $variant->prices->sortByDesc('price')->first());

})->group('product_variants');

it('can show a product variant with included prices', function () {
    /** @var TestCase $this */
    $variant = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->has(Price::factory()->count(3), 'prices')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('product_variants')
        ->includePaths('prices')
        ->get(serverUrl('/product_variants/'.$variant->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant);

    foreach ($variant->prices as $price) {
        $response->assertIsIncluded('prices', $price);
    }

})->group('product_variants');

it('can show a product variant with other variants included', function () {
    /** @var TestCase $this */
    $variants = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->count(3)
        ->create();

    $variant = $variants->first();
    $otherVariants = $variants->where('id', '!=', $variant->id);

    $response = $this
        ->jsonApi()
        ->expects('product_variants')
        ->includePaths('other_product_variants')
        ->get(serverUrl('/product_variants/').$variant->getRouteKey());

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant);

    foreach ($otherVariants as $variant) {
        $response->assertIsIncluded('product_variants', $variant);
    }

})->group('product_variants');

it('can show a product with other variants count', function () {
    /** @var TestCase $this */
    $variants = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->count(4)
        ->create();

    $variant = $variants->first();
    $otherVariants = $variants->where('id', '!=', $variant->id);

    $response = $this
        ->jsonApi()
        ->expects('product_variants')
        ->get(serverUrl("/product_variants/{$variant->getRouteKey()}?with_count=other_product_variants"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant);

    expect($response->json('data.relationships.other_product_variants.meta.count'))->toBe(3);
})->group('product_variants');
