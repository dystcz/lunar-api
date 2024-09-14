<?php

use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Support\Models\Actions\ModelType;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Contracts\ProductVariant as ProductVariantContract;

uses(TestCase::class, RefreshDatabase::class);

it('can list prices through relationship', function () {
    /** @var TestCase $this */

    /** @var Product $product */
    $product = ProductFactory::new()
        ->has(ProductVariantFactory::new()->count(3), 'variants')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects(ModelType::get(ProductVariantContract::class))
        ->get(serverUrl("/products/{$product->getRouteKey()}/product-variants"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($product->variants)
        ->assertDoesntHaveIncluded();
})->group('products');
