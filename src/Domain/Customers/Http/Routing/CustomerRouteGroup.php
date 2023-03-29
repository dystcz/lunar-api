<?php

namespace Dystcz\LunarApi\Domain\Customers\Http\Routing;

use Dystcz\LunarApi\Domain\Customers\Http\Controllers\CustomersController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class CustomerRouteGroup extends RouteGroup
{
    public string $prefix = 'customers';

    public array $middleware = [];

    /**
     * Register routes.
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
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
