<?php

use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Lunar\Facades\ModelManifest;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
});

it('can list configured schemas', function () {
    /** @var TestCase $this */

    /** @var \Dystcz\LunarApi\LunarApiConfig $config */
    $config = App::make('lunar-api-config');

    $schemas = $config->getSchemas();

    expect($schemas)->toBeInstanceOf(\Illuminate\Support\Collection::class);

})->group('config');

it('can list configured route groups', function () {
    /** @var TestCase $this */

    /** @var \Dystcz\LunarApi\LunarApiConfig $config */
    $config = App::make('lunar-api-config');

    $routes = $config->getRoutes();

    expect($routes)->toBeInstanceOf(\Illuminate\Support\Collection::class);

})->group('config');

it('can list configured models for lunar model manifest', function () {
    /** @var TestCase $this */

    /** @var \Dystcz\LunarApi\LunarApiConfig $config */
    $config = App::make('lunar-api-config');

    $models = $config->getModels();

    expect($models)->toBeInstanceOf(\Illuminate\Support\Collection::class);

    foreach ($models as $contract => $model) {
        $this->assertSame($model, ModelManifest::get($contract));
    }

})->group('config');

it('can list configured policies', function () {
    /** @var TestCase $this */

    /** @var \Dystcz\LunarApi\LunarApiConfig $config */
    $config = App::make('lunar-api-config');

    $policies = $config->getPolicies();

    expect($policies)->toBeInstanceOf(\Illuminate\Support\Collection::class);

})->group('config');
