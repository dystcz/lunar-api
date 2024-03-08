<?php

use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('cannot list prices on their own', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('prices')
        ->get('/api/v1/prices');

    $response->assertErrorStatus([
        'status' => '404',
        'title' => 'Not Found',
    ]);
})->group('prices', 'policies');
