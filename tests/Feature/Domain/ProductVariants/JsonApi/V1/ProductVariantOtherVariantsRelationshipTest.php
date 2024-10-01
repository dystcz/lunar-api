<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can list other product variants through relationship', function () {
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
        ->get(serverUrl("/product-variants/{$variant->getRouteKey()}/other-product-variants"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($otherVariants)
        ->assertDoesntHaveIncluded();
})->group('product-variants');

it('can count other product variants', function () {
    /** @var TestCase $this */
    $variants = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->count(3)
        ->create();

    $variant = $variants->first();

    $response = $this
        ->jsonApi()
        ->expects('product-variants')
        ->get(serverUrl("/product-variants/{$variant->getRouteKey()}?with_count=other-product-variants"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant);

    expect($response->json('data.relationships.other-product-variants.meta.count'))->toBe(2);
})->group('product-variants', 'counts');
