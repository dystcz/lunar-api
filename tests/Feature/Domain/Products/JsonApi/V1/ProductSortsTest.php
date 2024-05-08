<?php

use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Product;

uses(TestCase::class, RefreshDatabase::class);

it('sort products by recently viewed', function () {
    /** @var TestCase $this */
    $leastViewedProduct = ProductFactory::new()->create();
    $mostViewedProduct = ProductFactory::new()->create();

    // one hit for least viewed product
    $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl("/products/{$leastViewedProduct->getRouteKey()}"));

    // two hits for most viewed product
    $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl("/products/{$mostViewedProduct->getRouteKey()}"));

    $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl("/products/{$mostViewedProduct->getRouteKey()}"));

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->sort('recently_viewed')
        ->get('http://localhost/api/v1/products');

    $response->assertFetchedManyInOrder([$mostViewedProduct, $leastViewedProduct]);
})->group('products', 'sorts')->skip('This test should be moved to lunar-product-views package.');
