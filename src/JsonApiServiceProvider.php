<?php

namespace Dystcz\LunarApi;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class JsonApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        \LaravelJsonApi\Laravel\LaravelJsonApi::defaultResource(
            \Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource::class,
        );
        \LaravelJsonApi\Laravel\LaravelJsonApi::defaultAuthorizer(
            \Dystcz\LunarApi\Domain\JsonApi\Authorizers\Authorizer::class,
        );
        \LaravelJsonApi\Laravel\LaravelJsonApi::defaultQuery(
            \Dystcz\LunarApi\Domain\JsonApi\Queries\Query::class,
        );
        \LaravelJsonApi\Laravel\LaravelJsonApi::defaultCollectionQuery(
            \Dystcz\LunarApi\Domain\JsonApi\Queries\CollectionQuery::class,
        );
        \LaravelJsonApi\Laravel\LaravelJsonApi::withCountQueryParameter(
            'with-count',
        );
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Register custom repository
        $this->app->bind(
            \LaravelJsonApi\Eloquent\Repository::class,
            fn () => \Dystcz\LunarApi\Domain\JsonApi\Eloquent\Repository::class,
        );

        // Register schema extension implementation
        $this->app->bind(
            \Dystcz\LunarApi\Base\Contracts\SchemaExtension::class,
            fn (Application $app, mixed $params) => new \Dystcz\LunarApi\Base\Extensions\SchemaExtension(...$params),
        );

        // Register schema manifest implementation
        $this->app->singleton(
            \Dystcz\LunarApi\Base\Contracts\SchemaManifest::class,
            fn (Application $app) => new \Dystcz\LunarApi\Base\Manifests\SchemaManifest,
        );

        // Register resource extension implementation
        $this->app->bind(
            \Dystcz\LunarApi\Base\Contracts\ResourceExtension::class,
            fn (Application $app, mixed $params) => new \Dystcz\LunarApi\Base\Extensions\ResourceExtension(...$params),
        );

        // Register resource manifest implementation
        $this->app->singleton(
            \Dystcz\LunarApi\Base\Contracts\ResourceManifest::class,
            fn (Application $app) => new \Dystcz\LunarApi\Base\Manifests\ResourceManifest,
        );
    }
}
