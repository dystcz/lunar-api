<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Database\Factories\ProductFactory;

uses(\Dystcz\LunarApi\Tests\TestCase::class, RefreshDatabase::class);

it('can read products associations', function () {
    $productA = ProductFactory::new()->create();
    $productB = ProductFactory::new()->create();

    $productA->associate(
        $productB,
        \Lunar\Models\ProductAssociation::CROSS_SELL
    );

    $self = 'http://localhost/api/v1/products/'.$productA->getRouteKey();

    $response = $this
        ->jsonApi()
        ->expects('products')
        ->includePaths('associations.target')
        ->get($self);

    $response->assertFetchedOne($productA)
        ->assertIsIncluded('associations', $productA->associations->first());
});
