<?php

use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Dystcz\LunarApi\Domain\ProductAssociations\Models\ProductAssociation;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can list product associations through relationship', function () {
    /** @var TestCase $this */

    /** @var Product $productA */
    $productA = Product::factory()->create();

    /** @var Product $productB */
    $productB = Product::factory()
        ->has(ProductVariantFactory::new()->has(Price::factory()), 'variants')
        ->create();

    $productA->associate(
        $productB,
        ProductAssociation::UP_SELL
    );

    $response = $this
        ->jsonApi()
        ->expects('associations')
        ->get(serverUrl("/products/{$productA->getRouteKey()}/associations"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($productA->associations)
        ->assertDoesntHaveIncluded();
})->group('products');
