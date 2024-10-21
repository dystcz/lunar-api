<?php

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
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

test('product variants cannot be updated', function () {
    /** @var TestCase $this */
    $response = $this->updateTest('product_variants', ProductVariant::class, []);

    $response->assertErrorStatus([
        'status' => '405',
        'title' => 'Method Not Allowed',
    ]);
})->group('variants', 'policies');
