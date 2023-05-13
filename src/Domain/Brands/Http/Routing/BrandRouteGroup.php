<?php

namespace Dystcz\LunarApi\Domain\Brands\Http\Routing;

use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;

class BrandRouteGroup extends RouteGroup implements RouteGroupContract
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
                $server->resource($this->getPrefix(), JsonApiController::class)
                    ->relationships(function ($relationships) {
                        $relationships->hasOne('default_url')->readOnly();
                    })
                    ->only('index', 'show')
                    ->readOnly();
            });
    }
}
