<?php

namespace Dystcz\LunarApi\Domain\Urls\Http\Routing;

use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;

class UrlRouteGroup extends RouteGroup implements RouteGroupContract
{
    public string $prefix = 'urls';

    public array $middleware = [];

    /**
     * Register routes.
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function ($server) {
                $server->resource($this->getPrefix(), JsonApiController::class)
                    ->only('index', 'show')
                    ->readOnly();
            });
    }
}
