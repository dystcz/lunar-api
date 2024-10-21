<?php

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->user = User::factory()
        ->has(Customer::factory())
        ->create();
});

test('products cannot be deleted', function () {
    /** @var TestCase $this */
    $response = $this->deleteTest('products', Product::class);

    $response->assertErrorStatus([
        'status' => '405',
        'title' => 'Method Not Allowed',
    ]);
})->group('products', 'policies');
