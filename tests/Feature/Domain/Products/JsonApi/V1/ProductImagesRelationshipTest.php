<?php

use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can list product images through relationship', function () {
    /** @var TestCase $this */
    $product = ProductFactory::new()
        ->withImages()
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('media')
        ->get(serverUrl("/products/{$product->getRouteKey()}/images"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($product->images)
        ->assertDoesntHaveIncluded();
})->group('products');

it('can count product images', function () {
    /** @var TestCase $this */
    $product = ProductFactory::new()
        ->withImages(3)
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl("/products/{$product->getRouteKey()}?with_count=images"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($product);

    expect($response->json('data.relationships.images.meta.count'))->toBe(3);
})->group('products');
