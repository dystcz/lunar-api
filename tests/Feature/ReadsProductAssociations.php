<?php

use Dystcz\LunarApi\Domain\Media\Factories\MediaFactory;
use Dystcz\LunarApi\Domain\Prices\Factories\PriceFactory;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductAssociations\Models\ProductAssociation;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can read products associations', function () {
    /** @var Product $productA */
    $productA = ProductFactory::new()->create();

    /** @var Product $productB */
    $productB = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->has(PriceFactory::new()),
            'variants'
        )
        ->has(MediaFactory::new()->thumbnail(), 'thumbnail')
        ->create();

    $productA->associate(
        $productB,
        ProductAssociation::CROSS_SELL
    );

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->includePaths(
            'associations',
            // 'associations.target.thumbnail'
        )
        ->get('/api/v1/products/'.$productA->getRouteKey());

    $response->assertFetchedOne($productA);
        // ->assertIsIncluded('associations', $productA->associations->first())
        // ->assertIsIncluded('products', $productB)
        // ->assertIsIncluded('media', $productB->thumbnail)
        // ->assertIsIncluded('variants', $productB->variants->first())
        // ->assertIsIncluded('prices', $productB->variants->first()->prices->first());
});
