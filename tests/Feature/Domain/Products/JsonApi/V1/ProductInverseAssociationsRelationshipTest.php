<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Price;
use Lunar\Models\ProductAssociation;

uses(TestCase::class, RefreshDatabase::class);

it('can list product inverse associations through relationship', function () {
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
        ->expects('associations')
        ->get(serverUrl("/products/{$productB->getRouteKey()}/inverse_associations"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($productB->inverseAssociations)
        ->assertDoesntHaveIncluded();
})->group('products');
