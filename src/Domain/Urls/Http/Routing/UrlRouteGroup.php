<?php

namespace Dystcz\LunarApi\Domain\Urls\Http\Routing;

use Dystcz\LunarApi\Domain\Urls\Contracts\UrlsController;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class UrlRouteGroup extends RouteGroup implements RouteGroupContract
{
    /**
     * Register routes.
     */
    public function routes(): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $server->resource($this->getPrefix(), UrlsController::class)
                    ->only('index', 'show')
                    ->readOnly();
            });
    }
}
