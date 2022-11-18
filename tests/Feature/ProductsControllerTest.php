<?php

namespace Dystcz\LunarApi\Tests\Feature\Domain\Products\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Lunar\Database\Factories\ProductFactory;

uses(\Dystcz\LunarApi\Tests\TestCase::class, RefreshDatabase::class);

it('can list all products', function () {
    ProductFactory::new()->count(10)->create();

    $response = $this->get(Config::get('lunar-api.route_prefix').'/products');

    $response->assertStatus(200);

    expect($response->json('data'))->toHaveCount(10);
});
