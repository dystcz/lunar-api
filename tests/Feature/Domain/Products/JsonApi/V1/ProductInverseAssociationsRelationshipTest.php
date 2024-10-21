<?php

use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Dystcz\LunarApi\Domain\ProductAssociations\Models\ProductAssociation;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can list inverse productassociations through relationship', function () {
    /** @var TestCase $this */

    /** @var Product $productA */
    $productA = Product::factory()->create();

    /** @var Product $productB */
    $productB = Product::factory()
        ->has(ProductVariantFactory::new()->has(Price::factory()), 'variants')
        ->create();

    $productA->associate(
        $productB,
        ProductAssociation::CROSS_SELL
    );

    $response = $this
        ->jsonApi()
        ->expects('product_associations')
        ->get(serverUrl("/products/{$productB->getRouteKey()}/inverse_product_associations"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($productB->inverseAssociations)
        ->assertDoesntHaveIncluded();
})->group('products');

it('can count inverse product associations', function () {
    /** @var TestCase $this */

    /** @var Product $productA */
    $productA = Product::factory()->create();

    /** @var Product $productB */
    $productB = Product::factory()
        ->has(ProductVariantFactory::new()->has(Price::factory()), 'variants')
        ->create();

    $productA->associate(
        $productB,
        ProductAssociation::CROSS_SELL
    );

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl("/products/{$productB->getRouteKey()}?with_count=inverse_product_associations"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($productB);

    expect($response->json('data.relationships.inverse_product_associations.meta.count'))->toBe(1);
})->group('products', 'counts');
