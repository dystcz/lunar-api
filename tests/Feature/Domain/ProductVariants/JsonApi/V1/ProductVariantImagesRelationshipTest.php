<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can list images through relationship', function () {
    /** @var TestCase $this */
    $variants = ProductVariantFactory::new()
        ->for(Product::factory(), 'product')
        ->withImages(3)
        ->create();

    $variant = $variants->first();

    $response = $this
        ->jsonApi()
        ->expects('media')
        ->get(serverUrl("/product-variants/{$variant->getRouteKey()}/images"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($variant->images)
        ->assertDoesntHaveIncluded();
})->group('variants');
