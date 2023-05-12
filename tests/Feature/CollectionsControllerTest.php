<?php

use Dystcz\LunarApi\Domain\Prices\Factories\PriceFactory;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Database\Factories\CollectionFactory;

uses(TestCase::class, RefreshDatabase::class);

it('can list all collections', function () {
    /** @var TestCase $this */
    $collections = CollectionFactory::new()
        ->has(
            ProductFactory::new()
                ->has(
                    ProductVariantFactory::new()
                        ->has(PriceFactory::new(), 'basePrices'),
                    'variants'
                )
        )
        ->count(3)
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('collections')
        ->get('/api/v1/collections');

    $response->assertSuccessful()
        ->assertFetchedMany($collections);

    expect($response->json('data'))->toHaveCount(3);
});

it('can read collection detail', function () {
    /** @var TestCase $this */
    $collection = CollectionFactory::new()->create();

    $response = $this
        ->jsonApi()
        ->expects('collections')
        ->get('/api/v1/collections/'.$collection->getRouteKey());

    $response->assertSuccessful()
        ->assertFetchedOne($collection);
});

it('can read products in a collection', function () {
    $collection = CollectionFactory::new()
        ->has(
            ProductFactory::new()
                ->has(
                    ProductVariantFactory::new()
                        ->has(
                            PriceFactory::new()
                        )
                        ->count(1),
                    'variants'
                )
                ->count(1)
        )
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('collections')
        ->includePaths('products')
        ->get('/api/v1/collections/'.$collection->getRouteKey());

    $response->assertSuccessful()
        ->assertFetchedOne($collection)
        ->assertIsIncluded('products', $collection->products->first());
});
