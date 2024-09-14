<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can list product variant images through relationship', function () {
    /** @var TestCase $this */
    $variant = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->withImages(3)
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('media')
        ->get(serverUrl("/product-variants/{$variant->getRouteKey()}/images"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($variant->images)
        ->assertDoesntHaveIncluded();
})->group('product-variants');

it('can count product variant images', function () {
    /** @var TestCase $this */
    $variant = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->withImages(3)
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('product-variants')
        ->get(serverUrl("/product-variants/{$variant->getRouteKey()}?with-count=images"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant);

    expect($response->json('data.relationships.images.meta.count'))->toBe(3);
})->group('products-variants', 'counts');
