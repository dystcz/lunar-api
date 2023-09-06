<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionQuery;
use Dystcz\LunarApi\Domain\JsonApi\Authorizers\Authorizer;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Repository;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceExtension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaExtension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest;
use Dystcz\LunarApi\Domain\JsonApi\Queries\Query;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Illuminate\Support\ServiceProvider;
use LaravelJsonApi\Laravel\LaravelJsonApi;

class JsonApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        LaravelJsonApi::defaultResource(JsonApiResource::class);
        LaravelJsonApi::defaultAuthorizer(Authorizer::class);
        LaravelJsonApi::defaultQuery(Query::class);
        LaravelJsonApi::defaultCollectionQuery(CollectionQuery::class);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Register custom repository
        $this->app->bind(
            \LaravelJsonApi\Eloquent\Repository::class,
            fn () => Repository::class,
        );

        // Register schema extension implementation
        $this->app->bind(
            \Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\SchemaExtension::class,
            fn ($app, $params) => new SchemaExtension(...$params),
        );

        // Register schema manifest implementation
        $this->app->singleton(
            \Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\SchemaManifest::class,
            fn ($app, $params) => new SchemaManifest($params),
        );

        // Register resource extension implementation
        $this->app->bind(
            \Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\ResourceExtension::class,
            fn ($app, $params) => new ResourceExtension(...$params),
        );

        // Register resource manifest implementation
        $this->app->singleton(
            \Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\ResourceManifest::class,
            fn ($app, $params) => new ResourceManifest($params),
        );
    }
}
