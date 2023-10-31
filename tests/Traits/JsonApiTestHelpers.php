<?php

namespace Dystcz\LunarApi\Tests\Traits;

use Dystcz\LunarApi\Tests\Data\TestInclude;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Testing\TestResponse;

trait JsonApiTestHelpers
{
    /**
     * @param  class-string  $model
     */
    public function indexTest(
        string $schemaType,
        string $model,
        int $modelCount = 5,
    ): TestResponse {

        $perPage = Config::get('lunar-api.general.pagination.per_page');

        /** @var TestCase $this */
        $models = $model::factory()
            ->count($modelCount)
            ->create();

        $response = $this
            ->jsonApi()
            ->expects($schemaType)
            ->get(serverUrl("/{$schemaType}"));

        $response
            ->assertSuccessful()
            ->assertFetchedMany(
                $models->when(
                    $modelCount > $perPage,
                    fn ($collection) => $collection->take($perPage),
                    fn ($collection) => $collection,
                ),
            );

        return $response;
    }

    /**
     * @param  class-string  $model
     * @param  array<string,array<string,string>>  $includes
     */
    public function indexWithIncludesTest(
        string $schemaType,
        string $model,
        int $modelCount,
        Collection $includes,
    ): TestResponse {

        $perPage = Config::get('lunar-api.general.pagination.per_page');

        /** @var TestCase $this */

        /** @var Factory $factory */
        $factory = $model::factory()
            ->count($modelCount);

        foreach ($includes as $include) {
            /** @var TestInclude $include */
            if (! $include->factory || ! $include->factory_relation) {
                continue;
            }

            $factory = $factory->{$include->factory_relation_method}($include->factory, $include->factory_relation);
        }

        $models = $factory->create();

        $response = $this
            ->jsonApi()
            ->expects($schemaType)
            ->includePaths(...$includes->keys())
            ->get(serverUrl("/{$schemaType}"));

        $included = Collection::make($includes)
            ->flatMap(function (TestInclude $include, string $includePath) use ($models) {
                return mapModelsToResponseData(
                    $include->type,
                    $include->getRelation($models),
                );
            });

        $response
            ->assertSuccessful()
            ->assertFetchedMany(
                $models->when(
                    $modelCount > $perPage,
                    fn ($collection) => $collection->take($perPage),
                    fn ($collection) => $collection,
                ),
            )
            ->assertIncluded($included);

        return $response;
    }

    /**
     * @param  class-string  $model
     */
    public function paginationTest(
        string $schemaType,
        string $model,
        int $modelCount = 20,
        int $page = 1,
        int $perPage = 24,
    ): TestResponse {
        /** @var TestCase $this */
        $models = $model::factory()
            ->count($modelCount)
            ->create();

        $response = $this
            ->jsonApi()
            ->expects($schemaType)
            ->page(['number' => $page, 'size' => $perPage])
            ->get(serverUrl("/{$schemaType}"));

        $this->assertDatabaseCount($models->first()->getTable(), $modelCount);

        $response
            ->assertSuccessful()
            ->assertFetchedMany($models->take($perPage));

        return $response;
    }
}
