<?php

use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can list prices through relationship', function () {
    /** @var TestCase $this */
    $product = ProductFactory::new()
        ->has(ProductVariantFactory::new()->count(3), 'variants')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('variants')
        ->get(serverUrl("/products/{$product->getRouteKey()}/variants"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($product->variants)
        ->assertDoesntHaveIncluded();
})->group('products');
