<?php

use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;

// uses(\Dystcz\LunarApi\Tests\MySqlTestCase::class, RefreshDatabase::class);

it('filter products by recently viewed', function () {
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
})->skip('TODO');
