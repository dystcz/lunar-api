<?php

namespace Dystcz\LunarApi\Domain\Customers\Http\Routing;

use Dystcz\LunarApi\Domain\Customers\Http\Controllers\CustomersController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class CustomerRouteGroup extends RouteGroup
{
    /**
     * Register routes.
     */
    public function routes(): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->middleware('auth')
            ->resources(function ($server) {
                $server->resource($this->getPrefix(), CustomersController::class)
                    ->relationships(function ($relationships) {
                        $relationships->hasMany('orders')->readOnly();
                        $relationships->hasMany('addresses')->readOnly();
                    })
                    ->only('show', 'update');
            });
    }
}
