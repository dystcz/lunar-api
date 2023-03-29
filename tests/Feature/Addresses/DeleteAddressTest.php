<?php

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Models\Address;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->has(Customer::factory())->create());

    $this->address = Address::factory()->create(['customer_id' => Auth::user()->customers->first()->id]);
});

it('can be deleted', function () {
    $response = $this
        ->jsonApi()
        ->expects('addresses')
        ->delete('/api/v1/addresses/'.$this->address->getRouteKey());

    $response->assertNoContent();

    $this->assertDatabaseMissing($this->address->getTable(), [
        'id' => $this->address->getRouteKey(),
    ]);
});
