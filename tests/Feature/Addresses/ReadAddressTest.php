<?php

use Dystcz\LunarApi\Domain\Addresses\Models\Address;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
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

it('can show address', function () {
    /** @var TestCase $this */
    $model = Address::factory()
        ->create([
            'customer_id' => $this->user->customers->first()->getKey(),
        ]);

    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->expects('addresses')
        ->get(serverUrl("/addresses/{$model->getRouteKey()}"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($model);

})->group('addresses');

it('can show address with country and customer included', function () {
    /** @var TestCase $this */
    $model = Address::factory()
        ->create([
            'customer_id' => $this->user->customers->first()->getKey(),
        ]);

    /** @var TestCase $this */
    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->expects('addresses')
        ->includePaths('country', 'customer')
        ->get(serverUrl("/addresses/{$model->getRouteKey()}"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($model)
        ->assertIncluded([
            mapModelsToResponseData('countries', collect([$model->country]))->first(),
            mapModelsToResponseData('customers', collect([$model->customer]))->first(),
        ]);

})->group('addresses');
