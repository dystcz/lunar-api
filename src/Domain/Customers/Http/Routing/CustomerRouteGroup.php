<?php

namespace Dystcz\LunarApi\Domain\Customers\Http\Routing;

use Dystcz\LunarApi\Domain\Customers\Contracts\CustomersController;
use Dystcz\LunarApi\Facades\LunarApi;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class CustomerRouteGroup extends RouteGroup
{
    /**
     * Register routes.
     */
    public function routes(): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->middleware('auth:'.LunarApi::getAuthGuard())
            ->resources(function (ResourceRegistrar $server) {
                $server->resource($this->getPrefix(), CustomersController::class)
                    ->relationships(function (Relationships $relationships) {
                        $relationships->hasMany('orders')->readOnly();
                        $relationships->hasMany('addresses')->readOnly();
                    })
                    ->only('show', 'update');
            });
    }
}
