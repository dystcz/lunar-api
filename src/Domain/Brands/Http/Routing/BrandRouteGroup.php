<?php

namespace Dystcz\LunarApi\Domain\Brands\Http\Routing;

use Dystcz\LunarApi\Domain\Brands\Contracts\BrandsController;
use Dystcz\LunarApi\Routing\Contracts\RouteGroup as RouteGroupContract;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class BrandRouteGroup extends RouteGroup implements RouteGroupContract
{
    /**
     * Register routes.
     */
    public function routes(): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $server->resource($this->getPrefix(), BrandsController::class)
                    ->relationships(function (Relationships $relationships) {
                        $relationships->hasOne('default-url')->readOnly();
                    })
                    ->only('index', 'show')
                    ->readOnly();
            });
    }
}
