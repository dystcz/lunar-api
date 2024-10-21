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

test('prices cannot be updated', function () {
    /** @var TestCase $this */
    $response = $this->updateTest('prices', Product::class, []);

    $response->assertErrorStatus([
        'status' => '404',
        'title' => 'Not Found',
    ]);
})->group('prices', 'policies');
