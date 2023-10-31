<?php

use Dystcz\LunarApi\Domain\Addresses\Models\Address;
use Dystcz\LunarApi\Domain\Countries\Models\Country;
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

it('can list addresses', function () {
    /** @var TestCase $this */
    $models = Address::factory()
        ->count(2)
        ->create([
            'customer_id' => $this->user->customers->first()->getKey(),
        ]);

    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->expects('addresses')
        ->get('/api/v1/addresses');

    $response
        ->assertSuccessful()
        ->assertFetchedMany($models);

})->group('addresses');

it('can list addresses with country included', function () {
    /** @var TestCase $this */
    $country = Country::factory()
        ->create();

    $models = Address::factory()
        ->count(2)
        ->create([
            'country_id' => $country->id,
            'customer_id' => $this->user->customers->first()->getKey(),
        ]);

    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->includePaths('country')
        ->expects('addresses')
        ->get('/api/v1/addresses');

    $response
        ->assertSuccessful()
        ->assertFetchedMany($models)
        ->assertIncluded(
            mapModelsToResponseData('countries', $models->pluck('country')),
        );

})->group('addresses');

it('can list addresses with customer included', function () {

    /** @var TestCase $this */
    $models = Address::factory()
        ->count(2)
        ->create([
            'customer_id' => $this->user->customers->first()->getKey(),
        ]);

    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->includePaths('customer')
        ->expects('addresses')
        ->get('/api/v1/addresses');

    $response
        ->assertSuccessful()
        ->assertFetchedMany($models)
        ->assertIncluded(
            mapModelsToResponseData('customers', $models->pluck('customer')),
        );

})->group('addresses');

it('can list addresses with countries and customers included', function () {
    /** @var TestCase $this */
    $models = Address::factory()
        ->count(3)
        ->create([
            'customer_id' => $this->user->customers->first()->getKey(),
        ]);

    /** @var TestCase $this */
    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->expects('addresses')
        ->includePaths('country', 'customer')
        ->get(serverUrl('/addresses'));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($models)
        ->assertIncluded([
            ...mapModelsToResponseData('countries', $models->pluck('country')),
            ...mapModelsToResponseData('customers', $models->pluck('customer')),
        ]);

})->group('addresses');
