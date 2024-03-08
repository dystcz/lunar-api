<?php

use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

test('users cannot create new shipping options', function () {
    /** @var TestCase $this */
    $response = $this->createTest('shipping-options', []);

    $response->assertErrorStatus([
        'status' => '405',
        'title' => 'Method Not Allowed',
    ]);
})->group('shipping-options', 'policies');
