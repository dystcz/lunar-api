<?php

namespace Dystcz\LunarApi\Domain\Filters\Http\Routing;

use Dystcz\LunarApi\Domain\Filters\Http\Controllers\FiltersController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class FilterRouteGroup extends RouteGroup
{
    public array $middleware = [];

    /**
     * Register routes.
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function ($server) {
                $server->resource($this->getPrefix(), FiltersController::class)
                    ->only('index')
                    ->readonly();
            });
    }
}
