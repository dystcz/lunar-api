<?php

use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Product;

uses(TestCase::class, RefreshDatabase::class);

it('can filter a single product by its slug', function () {
    /** @var TestCase $this */
    generateUrls();

    $product = Product::factory()->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl("/products/{$product->getRouteKey()}?filter[url][slug]={$product->defaultUrl->slug}"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($product)
        ->assertDoesntHaveIncluded();

    dontGenerateUrls();
})->group('products');

it('filter products by recently viewed', function () {
    /** @var TestCase $this */
    $leastViewedProduct = ProductFactory::new()->create();
    $mostViewedProduct = ProductFactory::new()->create();

    // one hit for least viewed product
    $this
        ->jsonApi()
        ->expects('products')
        ->get('http://localhost/api/v1/products/'.$leastViewedProduct->getRouteKey());

    // two hits for most viewed product
    $this
        ->jsonApi()
        ->expects('products')
        ->get('http://localhost/api/v1/products/'.$mostViewedProduct->getRouteKey());

    $this
        ->jsonApi()
        ->expects('products')
        ->get('http://localhost/api/v1/products/'.$mostViewedProduct->getRouteKey());

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->sort('recently_viewed')
        ->get('http://localhost/api/v1/products');

    $response->assertFetchedManyInOrder([$mostViewedProduct, $leastViewedProduct]);
})->todo();
