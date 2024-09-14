<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Collection;

uses(TestCase::class, RefreshDatabase::class);

it('can list product collections through relationship', function () {
    /** @var TestCase $this */
    $product = Product::factory()
        ->has(Collection::factory()->count(2), 'collections')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('collections')
        ->get(serverUrl("/products/{$product->getRouteKey()}/collections"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($product->collections)
        ->assertDoesntHaveIncluded();
})->group('products');

it('can count product collections', function () {
    /** @var TestCase $this */
    $product = Product::factory()
        ->has(Collection::factory()->count(4), 'collections')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl("/products/{$product->getRouteKey()}?with-count=collections"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($product);

    expect($response->json('data.relationships.collections.meta.count'))->toBe(4);
})->group('products', 'counts');
