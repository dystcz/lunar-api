<?php

use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApi\Support\Models\Actions\ModelType;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Contracts\ProductVariant as ProductVariantContract;

uses(TestCase::class, RefreshDatabase::class);

it('can show product variant detail', function () {
    /** @var TestCase $this */
    $variant = ProductVariant::factory()->create();

    $response = $this
        ->jsonApi()
        ->expects(ModelType::get(ProductVariantContract::class))
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
        ->expects(ModelType::get(ProductVariantContract::class))
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
        ->expects(ModelType::get(ProductVariantContract::class))
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
        ->expects(ModelType::get(ProductVariantContract::class))
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
        ->expects(ModelType::get(ProductVariantContract::class))
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
        ->expects(ModelType::get(ProductVariantContract::class))
        ->includePaths('lowest_price')
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
        ->expects(ModelType::get(ProductVariantContract::class))
        ->includePaths('highest_price')
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
        ->expects(ModelType::get(ProductVariantContract::class))
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
        ->expects(ModelType::get(ProductVariantContract::class))
        ->includePaths('other_variants')
        ->get(serverUrl('/product-variants/').$variant->getRouteKey());

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant);

    foreach ($otherVariants as $variant) {
        $response->assertIsIncluded(ModelType::get(ProductVariantContract::class), $variant);
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
        ->expects(ModelType::get(ProductVariantContract::class))
        ->get(serverUrl('/product-variants/').$variant->getRouteKey().'?withCount=other_variants');

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant);

    expect($response->json('data.relationships.other_variants.meta.count'))->toBe(3);
})->group('variants');
