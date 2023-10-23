<?php

use Dystcz\LunarApi\Domain\Collections\Models\Collection;
use Dystcz\LunarApi\Domain\Prices\Factories\PriceFactory;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can list all collections', function () {
    /** @var TestCase $this */
    $models = Collection::factory()
        ->has(
            Product::factory()
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
        ->assertFetchedMany($models);

    expect($response->json('data'))->toHaveCount(3);
})->group('collections');

it('can read collection detail', function () {
    /** @var TestCase $this */
    $model = Collection::factory()->create();

    $response = $this
        ->jsonApi()
        ->expects('collections')
        ->get('/api/v1/collections/'.$model->getRouteKey());

    $response->assertSuccessful()
        ->assertFetchedOne($model);
})->group('collections');

it('can read products in a collection', function () {
    /** @var TestCase $this */
    $model = Collection::factory()
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
        ->get('/api/v1/collections/'.$model->getRouteKey());

    $response->assertSuccessful()
        ->assertFetchedOne($model)
        ->assertIsIncluded('products', $model->products->first());
})->group('collections');
