<?php

use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

test('users cannot create new brand', function () {
    /** @var TestCase $this */
    $response = $this->createTest('brands', []);

    $response->assertErrorStatus([
        'status' => '405',
        'title' => 'Method Not Allowed',
    ]);
})->group('brands', 'policies');
