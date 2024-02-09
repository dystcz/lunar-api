<?php

namespace Dystcz\LunarApi\Domain\ShippingOptions\Http\Routing;

use Dystcz\LunarApi\Domain\ShippingOptions\Http\Controllers\ShippingOptionsController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class ShippingOptionRouteGroup extends RouteGroup
{
    /**
     * Register routes.
     */
    public function routes(): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $server->resource($this->getPrefix(), ShippingOptionsController::class)
                    ->only('index')
                    ->readOnly();
            });
    }
}
