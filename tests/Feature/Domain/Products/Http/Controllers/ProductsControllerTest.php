<?php

use Illuminate\Support\Facades\Config;

uses(\Dystcz\GetcandyApi\Tests\TestCase::class);

it('can list all products', function () {
    $response = $this->get(Config::get('getcandy-api.route_prefix').'/products');
    $response->assertStatus(200);
});
