<?php

namespace Dystcz\LunarApi\Domain\ProductOptionValues\Http\Routing;

use Dystcz\LunarApi\Domain\ProductOptionValues\Http\Controllers\ProductOptionValueController;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class ProductOptionValueRouteGroup extends RouteGroup implements RouteGroupContract
{
    /**
     * Register routes.
     */
    public function routes(): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $server
                    ->resource($this->getPrefix(), ProductOptionValueController::class)
                    ->readOnly();
            });
    }
}
