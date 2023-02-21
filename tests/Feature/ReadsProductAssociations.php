<?php

use Dystcz\LunarApi\Domain\Media\Factories\MediaFactory;
use Dystcz\LunarApi\Domain\Prices\Factories\PriceFactory;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\ProductAssociation;

uses(TestCase::class, RefreshDatabase::class);

it('can read products associations', function () {
    /** @var Product $productB */
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

    $self = 'http://localhost/api/v1/products/' . $productA->getRouteKey();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->includePaths(
            'associations.target.variants.prices',
            'associations.target.thumbnail'
        )
        ->get($self);

    $response->assertFetchedOne($productA)
        ->assertIsIncluded('associations', $productA->associations->first())
        ->assertIsIncluded('products', $productB)
        ->assertIsIncluded('thumbnails', $productB->thumbnail)
        ->assertIsIncluded('variants', $productB->variants->first())
        ->assertIsIncluded('prices', $productB->variants->first()->prices->first());
});
