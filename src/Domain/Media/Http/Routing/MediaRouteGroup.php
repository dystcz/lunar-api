<?php

namespace Dystcz\LunarApi\Domain\Media\Http\Routing;

use Dystcz\LunarApi\Domain\Media\Http\Controllers\MediaController;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class MediaRouteGroup extends RouteGroup implements RouteGroupContract
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
                $server->resource($this->getPrefix(), MediaController::class)
                    ->only('show')
                    ->readOnly();
            });
    }
}
