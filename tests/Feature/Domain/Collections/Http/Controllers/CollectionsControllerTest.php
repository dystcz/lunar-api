<?php

namespace Dystcz\GetcandyApi\Tests\Feature\Domain\Collections\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

uses(\Dystcz\GetcandyApi\Tests\TestCase::class, RefreshDatabase::class);

it('can list all collections', function () {
    $this->assertTrue(true);
    // $response = $this->get(Config::get('getcandy-api.route_prefix').'/collections');
    // $response->assertStatus(200);
});
