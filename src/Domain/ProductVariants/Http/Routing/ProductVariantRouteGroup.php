<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Http\Routing;

use Dystcz\LunarApi\Domain\ProductVariants\Http\Controllers\ProductVariantsController;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class ProductVariantRouteGroup extends RouteGroup implements RouteGroupContract
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
                    ->resource($this->getPrefix(), ProductVariantsController::class)
                    ->readOnly();
            });
    }
}
