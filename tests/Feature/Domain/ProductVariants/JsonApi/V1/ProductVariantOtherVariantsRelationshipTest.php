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
        ->expects('product_variants')
        ->get(serverUrl("/product_variants/{$variant->getRouteKey()}/other_product_variants"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($otherVariants)
        ->assertDoesntHaveIncluded();
})->group('product_variants');

it('can count other product variants', function () {
    /** @var TestCase $this */
    $variants = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->count(3)
        ->create();

    $variant = $variants->first();

    $response = $this
        ->jsonApi()
        ->expects('product_variants')
        ->get(serverUrl("/product_variants/{$variant->getRouteKey()}?with_count=other_product_variants"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($variant);

    expect($response->json('data.relationships.other_product_variants.meta.count'))->toBe(2);
})->group('product_variants', 'counts');
