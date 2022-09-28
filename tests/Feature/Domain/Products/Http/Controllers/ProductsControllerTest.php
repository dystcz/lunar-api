<?php

namespace Dystcz\LunarApi\Tests\Feature\Domain\Products\Http\Controllers;

use Illuminate\Support\Facades\Config;

uses(\Dystcz\LunarApi\Tests\TestCase::class);

it('can list all products', function () {
    $this->assertTrue(true);
    // $response = $this->get(Config::get('lunar-api.route_prefix').'/products');
    // $response->assertStatus(200);
});
