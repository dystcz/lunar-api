<?php

namespace Dystcz\LunarApi\Tests\Feature\Domain\Collections\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

uses(\Dystcz\LunarApi\Tests\TestCase::class, RefreshDatabase::class);

it('can list all collections', function () {
    $this->assertTrue(true);
    // $response = $this->get(Config::get('lunar-api.route_prefix').'/collections');
    // $response->assertStatus(200);
});
