<?php

use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

test('users cannot create new payment options', function () {
    /** @var TestCase $this */
    $response = $this->createTest('payment_options', []);

    $response->assertErrorStatus([
        'status' => '405',
        'title' => 'Method Not Allowed',
    ]);
})->group('payment_options', 'policies');
