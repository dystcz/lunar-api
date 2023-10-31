<?php

namespace Dystcz\LunarApi\Domain\Currencies\Http\Routing;

use Dystcz\LunarApi\Domain\Currencies\Http\Controllers\CurrenciesController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class CurrencyRouteGroup extends RouteGroup
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
                $server->resource($this->getPrefix(), CurrenciesController::class)
                    ->only('index')
                    ->readOnly();
            });
    }
}
