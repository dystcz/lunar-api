<?php

namespace Dystcz\LunarApi\Tests\Feature\Domain\Products\Http\Controllers;

use Dystcz\LunarApi\Domain\Media\Factories\MediaFactory;
use Dystcz\LunarApi\Domain\Prices\Factories\PriceFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;

uses(\Dystcz\LunarApi\Tests\TestCase::class, RefreshDatabase::class);

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

    ray($response->json());

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
    $self = 'http://localhost/api/v1/products/1';

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get($self);

    ray($response->json());
})->skip();

it('can list product\'s images', function () {
    $product = ProductFactory::new()
        ->has(MediaFactory::new(), 'images')
        ->create();

    $self = 'http://localhost/api/v1/products/'.$product->getRouteKey();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->includePaths('images')
        ->get($self);

    $response->assertFetchedOne($product);
});

it('can read product\'s variants count', function () {
    $product = ProductFactory::new()
        ->has(
            ProductVariantFactory::new()->has(PriceFactory::new())->count(5),
            'variants'
        )
        ->create();

    $response = $this->get(Config::get('lunar-api.route_prefix').'/products/'.$product->defaultUrl->slug.'?include=variantsCount');

    $response->assertStatus(200);

    expect($response->json('data.attributes.variants_count'))->toBe(5);
})->skip();
