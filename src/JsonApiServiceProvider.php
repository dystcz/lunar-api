<?php

namespace Dystcz\LunarApi;

use Illuminate\Support\ServiceProvider;
use LaravelJsonApi\Laravel\LaravelJsonApi;

class JsonApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        LaravelJsonApi::defaultResource(\Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource::class);
        LaravelJsonApi::defaultAuthorizer(\Dystcz\LunarApi\Domain\JsonApi\Authorizers\Authorizer::class);
        LaravelJsonApi::defaultQuery(\Dystcz\LunarApi\Domain\JsonApi\Queries\Query::class);
        LaravelJsonApi::defaultCollectionQuery(\Dystcz\LunarApi\Domain\Collections\JsonApi\V1\CollectionQuery::class);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Register custom repository
        $this->app->bind(
            \LaravelJsonApi\Eloquent\Repository::class,
            fn () => \Dystcz\LunarApi\Domain\JsonApi\Eloquent\Repositories\Repository::class,
        );

        // Register schema extension implementation
        $this->app->bind(
            \Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\SchemaExtension::class,
            fn ($app, $args) => new \Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaExtension(...$args),
        );

        // Register schema manifest implementation
        $this->app->singleton(
            \Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\SchemaManifest::class,
            fn ($app, $args) => new \Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest($args),
        );

        // Register resource extension implementation
        $this->app->bind(
            \Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\ResourceExtension::class,
            fn ($app, $args) => new \Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceExtension(...$args),
        );

        // Register resource manifest implementation
        $this->app->singleton(
            \Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\ResourceManifest::class,
            fn ($app, $args) => new \Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest($args),
        );
    }
}
