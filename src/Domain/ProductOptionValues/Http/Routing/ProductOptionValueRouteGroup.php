<?php

namespace Dystcz\LunarApi\Domain\ProductOptionValues\Http\Routing;

use Dystcz\LunarApi\Domain\ProductOptionValues\Http\Controllers\ProductOptionValueController;
use Dystcz\LunarApi\Domain\ProductOptionValues\JsonApi\V1\ProductOptionValueSchema;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class ProductOptionValueRouteGroup extends RouteGroup implements RouteGroupContract
{
    public array $middleware = [];

    /**
     * Register routes.
     */
    public function routes(string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $server
                    ->resource(ProductOptionValueSchema::type(), ProductOptionValueController::class)
                    ->readOnly();
            });
    }
}
