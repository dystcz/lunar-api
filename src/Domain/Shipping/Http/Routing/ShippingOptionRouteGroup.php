<?php

namespace Dystcz\LunarApi\Domain\Shipping\Http\Routing;

use Dystcz\LunarApi\Domain\Shipping\Http\Controllers\ShippingOptionsController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class ShippingOptionRouteGroup extends RouteGroup
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
                $server->resource($this->getPrefix(), ShippingOptionsController::class)
                    ->only('index');
            });
    }
}
