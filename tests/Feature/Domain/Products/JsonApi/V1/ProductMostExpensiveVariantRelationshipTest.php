<?php

use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Support\Models\Actions\ModelType;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Contracts\ProductVariant as ProductVariantContract;

uses(TestCase::class, RefreshDatabase::class);

it('can read most expensive variant through relationship', function () {
    /** @var TestCase $this */
    $product = Product::factory()
        ->has(ProductVariantFactory::new()->withPrice()->count(4), 'variants')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects(ModelType::get(ProductVariantContract::class))
        ->get(serverUrl("/products/{$product->getRouteKey()}/most_expensive_variant"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne(
            $product->variants->sortBy(
                fn ($variant) => $variant->prices->sortByDesc('price')->first()->price,
            )->first(),
        )
        ->assertDoesntHaveIncluded();
})->group('products');
