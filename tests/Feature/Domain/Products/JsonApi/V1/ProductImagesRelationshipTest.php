<?php

use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can list images through relationship', function () {
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
