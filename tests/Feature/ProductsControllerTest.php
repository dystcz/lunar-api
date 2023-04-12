<?php

namespace Dystcz\LunarApi\Tests\Feature\Domain\Products\Http\Controllers;

use Dystcz\LunarApi\Domain\Media\Factories\MediaFactory;
use Dystcz\LunarApi\Domain\Prices\Factories\PriceFactory;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Database\Factories\TagFactory;

uses(TestCase::class, RefreshDatabase::class);

it('can list all products', function () {
    $products = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->has(PriceFactory::new())->count(2),
            'variants'
        )
        ->count(3)
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->includePaths('variants.prices')
        ->get('/api/v1/products');

    $response->assertFetchedMany($products);
});

it('can read product detail', function () {
    $product = ProductFactory::new()->create();

    $self = 'http://localhost/api/v1/products/'.$product->getRouteKey();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get($self);

    $response->assertFetchedOne($product);
});

it('return error response when product doesnt exists', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get('/api/v1/products/1');

    $response->assertErrorStatus([
        'status' => '404',
        'title' => 'Not Found',
    ]);
});

it('can list product\'s images', function () {
    /** @var TestCase $this */
    $product = ProductFactory::new()
        ->has(MediaFactory::new(), 'images')
        ->create();

    $self = 'http://localhost/api/v1/products/'.$product->getRouteKey();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->includePaths('images')
        ->get($self);

    $response->assertFetchedOne($product)
        ->assertIsIncluded('media', $product->images->first());
});

it('can read product\'s variants count', function () {
    /** @var TestCase $this */
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->has(PriceFactory::new())->count(5),
            'variants'
        )
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get('/api/v1/products/'.$product->id.'?withCount=variants');

    $response->assertSuccessful();

    expect($response->json('data.relationships.variants.meta.count'))->toBe(5);
});

it('can list product\'s tags', function () {
    /** @var TestCase $this */
    $product = ProductFactory::new()
        ->has(TagFactory::new()->count(2))
        ->create();

    $self = 'http://localhost/api/v1/products/'.$product->getRouteKey();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->includePaths('tags')
        ->get($self);

    $response->assertFetchedOne($product)
        ->assertIncluded([
            ['type' => 'tags', 'id' => $product->tags[0]->getRouteKey()],
            ['type' => 'tags', 'id' => $product->tags[1]->getRouteKey()],
        ]);
});
