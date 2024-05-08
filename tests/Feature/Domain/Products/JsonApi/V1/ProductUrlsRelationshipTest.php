<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    generateUrls();
});

it('can list urls through relationship', function () {
    /** @var TestCase $this */
    $product = Product::factory()
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('urls')
        ->get(serverUrl("/products/{$product->getRouteKey()}/urls"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($product->urls)
        ->assertDoesntHaveIncluded();
})->group('products');
