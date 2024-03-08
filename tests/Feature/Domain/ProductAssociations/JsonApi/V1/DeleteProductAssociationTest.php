<?php

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\ProductAssociations\Models\ProductAssociation;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->user = User::factory()
        ->has(Customer::factory())
        ->create();
});

test('product associations cannot be deleted', function () {
    /** @var TestCase $this */
    $response = $this->deleteTest('associations', ProductAssociation::class);

    $response->assertErrorStatus([
        'status' => '404',
        'title' => 'Not Found',
    ]);
})->group('associations', 'policies');
