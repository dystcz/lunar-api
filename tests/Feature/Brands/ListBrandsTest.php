<?php

use Dystcz\LunarApi\Domain\Brands\Models\Brand;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Media\Factories\MediaFactory;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->user = User::factory()
        ->has(Customer::factory())
        ->create();
});

it('can list all brands', function () {
    /** @var TestCase $this */
    $models = Brand::factory()
        ->count(20)
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('brands')
        ->get(serverUrl('/brands'));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($models->take(Config::get('lunar-api.general.pagination.per_page')));

    $this->assertDatabaseCount($models->first()->getTable(), 20);

})->group('brands');

it('can list brands with custom pagination', function () {
    /** @var TestCase $this */
    $models = Brand::factory()
        ->count(20)
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('brands')
        ->page(['number' => 1, 'size' => 24])
        ->get(serverUrl('/brands'));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($models->take(24));

    $this->assertDatabaseCount($models->first()->getTable(), 20);

})->group('brands');

test('brands can be listed with thumbnail included', function () {
    /** @var TestCase $this */
    $models = Brand::factory()
        ->count(5)
        ->has(MediaFactory::new()->thumbnail(), 'media')
        ->create();

    $response = $this
        ->jsonApi()
        ->expects('brands')
        ->includePaths('thumbnail')
        ->get(serverUrl('/brands'));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($models)
        ->assertIncluded(
            mapModelsToResponseData('media', $models->pluck('media')->flatten()),
        );
})->group('brands');
