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
        ->expects('product-variants')
        ->get(serverUrl('/product-variants/'.$variant->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant)
        ->assertDoesntHaveIncluded();
})->group('variants');

it('returns error response when product variant does not exists', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('product-variants')
        ->get(serverUrl('/product-variants/1'));

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
        ->expects('product-variants')
        ->includePaths('images')
        ->get(serverUrl('/product-variants/'.$variant->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant)
        ->assertIsIncluded('media', $variant->images->first());
})->group('variants');

it('can show a product variant with included thumbnail', function () {
    /** @var TestCase $this */

    /** @var ProductVariant $variant */
    $variant = ProductVariantFactory::new()
        ->withImages(3)
        ->withThumbnail()
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('product-variants')
        ->includePaths('thumbnail')
        ->get(serverUrl('/product-variants/'.$variant->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant)
        ->assertIsIncluded('media', $variant->thumbnail);
})->group('variants');

it('can show a product variant with included product', function () {
    /** @var TestCase $this */
    $variant = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('product-variants')
        ->includePaths('product')
        ->get(serverUrl('/product-variants/'.$variant->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant)
        ->assertIsIncluded('products', $variant->product);

})->group('variants');

it('can show a product variant with included lowest price', function () {
    /** @var TestCase $this */
    $variant = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->has(Price::factory()->count(5), 'prices')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('product-variants')
        ->includePaths('lowest-price')
        ->get(serverUrl('/product-variants/'.$variant->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant)
        ->assertIsIncluded('prices', $variant->prices->sortBy('price')->first());

})->group('variants');

it('can show a product variant with included highest price', function () {
    /** @var TestCase $this */
    $variant = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->has(Price::factory()->count(5), 'prices')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('product-variants')
        ->includePaths('highest-price')
        ->get(serverUrl('/product-variants/'.$variant->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant)
        ->assertIsIncluded('prices', $variant->prices->sortByDesc('price')->first());

})->group('variants');

it('can show a product variant with included prices', function () {
    /** @var TestCase $this */
    $variant = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->has(Price::factory()->count(3), 'prices')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('product-variants')
        ->includePaths('prices')
        ->get(serverUrl('/product-variants/'.$variant->getRouteKey()));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant);

    foreach ($variant->prices as $price) {
        $response->assertIsIncluded('prices', $price);
    }

})->group('variants');

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
        ->expects('product-variants')
        ->includePaths('other-variants')
        ->get(serverUrl('/product-variants/').$variant->getRouteKey());

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant);

    foreach ($otherVariants as $variant) {
        $response->assertIsIncluded('product-variants', $variant);
    }

})->group('variants');

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
        ->expects('product-variants')
        ->get(serverUrl("/product-variants/{$variant->getRouteKey()}?with-count=other-variants"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant);

    expect($response->json('data.relationships.other-variants.meta.count'))->toBe(3);
})->group('variants');
