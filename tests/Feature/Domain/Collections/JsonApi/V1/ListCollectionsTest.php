<?php

use Dystcz\LunarApi\Domain\CollectionGroups\Models\CollectionGroup;
use Dystcz\LunarApi\Domain\Collections\Models\Collection;
use Dystcz\LunarApi\Domain\Media\Factories\MediaFactory;
use Dystcz\LunarApi\Domain\Prices\Factories\PriceFactory;
use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Dystcz\LunarApi\Tests\Data\TestInclude;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection as IlluminateCollection;

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

    $response
        ->assertSuccessful()
        ->assertFetchedMany($models);

    expect($response->json('data'))->toHaveCount(3);
})->group('collections');

it('can list collections with included collection group', function () {
    /** @var TestCase $this */
    $models = Collection::factory()
        ->has(
            CollectionGroup::factory(),
            'group',
        )
        ->count(4)
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('collections')
        ->includePaths('group')
        ->get('/api/v1/collections');

    $response
        ->assertSuccessful()
        ->assertFetchedMany($models)
        ->assertIncluded(
            mapModelsToResponseData(
                'collection_groups',
                $models->pluck('group'),
            ),
        );

    expect($response->json('data'))->toHaveCount(4);

})->group('collections');

it('can list collections with included default url', function () {
    /** @var TestCase $this */
    generateUrls();

    $models = Collection::factory()
        ->count(3)
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('collections')
        ->includePaths('default_url')
        ->get('/api/v1/collections');

    $response
        ->assertSuccessful()
        ->assertFetchedMany($models)
        ->assertIncluded(
            mapModelsToResponseData(
                'urls',
                $models->pluck('defaultUrl'),
            ),
        );

    expect($response->json('data'))->toHaveCount(3);

})->group('collections');

it('can list collections with included products', function () {

    /** @var TestCase $this */
    $includes = collect([
        'products' => new TestInclude(
            type: 'products',
            relation: 'products',
        ),
        'products.prices' => new TestInclude(
            type: 'prices',
            relation: 'prices',
            relationCallback: fn (IlluminateCollection $models) => $models->pluck('products')->flatten()->pluck('prices')->flatten(),
        ),
        'products.lowest_price' => new TestInclude(
            type: 'prices',
            relation: 'lowestPrice',
            relationCallback: fn (IlluminateCollection $models) => $models->pluck('products')->flatten()->pluck('lowestPrice'),
        ),
        'products.thumbnail' => new TestInclude(
            type: 'media',
            relation: 'thumbnail',
            relationCallback: fn (IlluminateCollection $models) => $models->pluck('products')->flatten()->pluck('thumbnail'),
        ),
        'products.images' => new TestInclude(
            type: 'media',
            factory: Product::factory()
                ->has(MediaFactory::new()->thumbnail(), 'thumbnail')
                ->has(MediaFactory::new()->count(2), 'images')
                ->has(ProductVariant::factory()->has(Price::factory(), 'prices')->count(2), 'variants')
                ->count(3),
            factory_relation: 'products',
            relation: 'images',
            relationCallback: fn (IlluminateCollection $models) => $models->pluck('products')->flatten()->pluck('images')->flatten(),
        ),
    ]);

    $response = $this->indexWithIncludesTest('collections', Collection::class, 5, $includes);

})->group('collections');
