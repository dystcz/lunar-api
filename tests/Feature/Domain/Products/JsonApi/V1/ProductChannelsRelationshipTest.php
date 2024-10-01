<?php

use Dystcz\LunarApi\Domain\Channels\Models\Channel;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can list product channels through relationship', function () {
    /** @var TestCase $this */

    /** @var Product $product */
    $product = Product::factory()
        ->has(Channel::factory()->count(1), 'channels')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('channels')
        ->get(serverUrl("/products/{$product->getRouteKey()}/channels"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($product->channels)
        ->assertDoesntHaveIncluded();
})->group('products');

it('can count product channels', function () {
    /** @var TestCase $this */

    /** @var Product $product */
    $product = Product::factory()
        ->has(Channel::factory()->count(2), 'channels')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->get(serverUrl("/products/{$product->getRouteKey()}?with_count=channels"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($product);

    // Expect 3, because a channel gets created after the product is created (HasChannels trait)
    expect($response->json('data.relationships.channels.meta.count'))->toBe(3);
})->group('products', 'counts');
