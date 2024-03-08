<?php

use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('cannot read price on its own', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('prices')
        ->get('/api/v1/prices/1');

    $response->assertErrorStatus([
        'status' => '404',
        'title' => 'Not Found',
    ]);
})->group('prices', 'policies');
