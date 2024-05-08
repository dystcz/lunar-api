<?php

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
})->group('products', 'filters');
