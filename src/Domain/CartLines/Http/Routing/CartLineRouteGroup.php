<?php

namespace Dystcz\LunarApi\Domain\CartLines\Http\Routing;

use Dystcz\LunarApi\Domain\CartLines\Contracts\CartLinesController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class CartLineRouteGroup extends RouteGroup
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
                    ->resource($this->getPrefix(), CartLinesController::class)
                    ->only('update', 'destroy', 'store');
            });
    }
}
