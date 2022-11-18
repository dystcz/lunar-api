<?php

namespace Dystcz\LunarApi\Tests\Feature\Domain\Collections\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Lunar\Database\Factories\CollectionFactory;
use Lunar\Database\Factories\PriceFactory;
use Lunar\Database\Factories\ProductFactory;
use Lunar\Database\Factories\ProductVariantFactory;

uses(\Dystcz\LunarApi\Tests\TestCase::class, RefreshDatabase::class);

it('can list all collections', function () {
    CollectionFactory::new()
        ->has(
            ProductFactory::new()
                ->has(
                    ProductVariantFactory::new()
                        ->has(PriceFactory::new(), 'basePrices'),
                    'variants'
                )
        )
        ->count(3)
        ->create();

    $response = $this->get(
        Config::get('lunar-api.route_prefix').
        '/collections'.
        '?include=products.variants.basePrices,products.defaultUrl'.
        '&fields[lunar_collections]=id,attribute_data'
    );

    $response->assertStatus(200);

    expect($response->json('data'))->toHaveCount(3);
});

it('can read collection detail', function () {
    $collection = CollectionFactory::new()->create();

    $response = $this->get(Config::get('lunar-api.route_prefix').'/collections/'.$collection->defaultUrl->slug);

    ray($response->json());

    $response->assertStatus(200);

    expect($response->json('data.id'))->toBe((string) $collection->id);
});

it('can read products in a collection', function () {
    $collection = CollectionFactory::new()
        ->has(
            ProductFactory::new()
                ->has(
                    ProductVariantFactory::new()
                        ->has(
                            PriceFactory::new()
                        )
                        ->count(1),
                    'variants'
                )
                ->count(1)
        )
        ->create();

    $response = $this->get(Config::get('lunar-api.route_prefix').'/collections/'.$collection->defaultUrl->slug.'?include=products');

    ray($response->json());

    $response->assertStatus(200);
});
