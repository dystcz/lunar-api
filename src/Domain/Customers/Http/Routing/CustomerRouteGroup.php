<?php

namespace Dystcz\LunarApi\Domain\Customers\Http\Routing;

use Dystcz\LunarApi\Domain\Customers\Http\Controllers\CustomersController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class CustomerRouteGroup extends RouteGroup
{
    /** @var string */
    public string $prefix = 'customers';

    /** @var array */
    public array $middleware = [];

    /**
     * Register routes.
     *
     * @param  null|string  $prefix
     * @param  array|string  $middleware
     * @return void
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function ($server) {
                $server->resource($this->getPrefix(), CustomersController::class)
                    ->relationships(function ($relationships) {
                        $relationships->hasMany('orders')->readOnly();
                    })
                    ->only('');
            });
    }
}
