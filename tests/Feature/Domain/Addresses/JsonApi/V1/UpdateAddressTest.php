<?php

use Dystcz\LunarApi\Domain\Addresses\Models\Address;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Facades\LunarApi;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

uses(TestCase::class, RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->user = User::factory()
        ->has(Customer::factory())
        ->create();

});

it('can update an address', function () {
    /** @var TestCase $this */
    $model = Address::factory()
        ->create([
            'customer_id' => $this->user->customers->first()->getKey(),
            'postcode' => $this->faker->postcode,
        ]);

    $id = $model->getRouteKey();

    $data = [
        'id' => (string) $id,
        'type' => 'addresses',
        'attributes' => [
            'first_name' => 'John',
            'last_name' => $model->last_name,
            'company_name' => $model->company_name,
            'city' => $model->city,
            'line_one' => $model->line_one,
            'line_two' => $model->line_two,
            'line_three' => $model->line_three,
            'postcode' => $model->postcode,
            'company_in' => '123456789',
            'company_tin' => '987654321',
        ],
    ];

    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->expects('addresses')
        ->withData($data)
        ->patch(serverUrl("addresses/{$id}"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($model);

    if (LunarApi::usesHashids()) {
        $id = decodeHashedId($model, $id);
    }

    $this->assertDatabaseHas($model->getTable(), [
        'id' => $id,
        'first_name' => 'John',
        'meta' => json_encode(
            array_merge(
                $model->meta?->toArray() ?? [],
                [
                    'company_in' => '123456789',
                    'company_tin' => '987654321',
                ],
            ),
        ),
    ]);
})->group('addresses');

it('can update only address belonging to logged in user', function () {
    /** @var TestCase $this */
    $user2 = User::factory()
        ->has(Customer::factory())
        ->create();

    $model = Address::factory()
        ->create([
            'customer_id' => $user2->customers->first()->getKey(),
            'postcode' => $this->faker->postcode,
        ]);

    $id = $model->getRouteKey();

    $data = [
        'id' => (string) $id,
        'type' => 'addresses',
        'attributes' => [
            'first_name' => 'John',
            'last_name' => $model->last_name,
            'company_name' => $model->company_name,
            'city' => $model->city,
            'line_one' => $model->line_one,
            'line_two' => $model->line_two,
            'line_three' => $model->line_three,
            'postcode' => $model->postcode,
            'company_in' => '123456789',
            'company_tin' => '987654321',
        ],
    ];

    $id = $model->getRouteKey();

    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->expects('addresses')
        ->delete(serverUrl("addresses/{$id}"));

    $response->assertErrorStatus([
        'detail' => 'This action is unauthorized.',
        'status' => '403',
        'title' => 'Forbidden',
    ]);

    $this->assertDatabaseMissing($model->getTable(), [
        'id' => $id,
        'first_name' => 'John',
        'meta' => json_encode(
            array_merge(
                $model->meta?->toArray() ?? [],
                [
                    'company_in' => '123456789',
                    'company_tin' => '987654321',
                ],
            ),
        ),
    ]);
})->group('addresses', 'policies');
