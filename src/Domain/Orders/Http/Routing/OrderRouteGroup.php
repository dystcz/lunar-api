<?php

namespace Dystcz\LunarApi\Domain\Orders\Http\Routing;

use Dystcz\LunarApi\Routing\RouteGroup;

class OrderRouteGroup extends RouteGroup
{
    /** @var string */
    public string $prefix = 'orders';

    /** @var array */
    public array $middleware = [];

    /**
     * Register routes.
     *
     * @param null|string  $prefix
     * @param array|string $middleware
     * @return void
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        // JsonApiRoute::server('v1')
        //     ->prefix('v1')
        //     ->resources(function ($server) {
        //         $server->resource($this->getPrefix(), OrdersController::class)
        //             ->relationships(function ($relationships) {
        //                 $relationships->hasMany('orders')->readOnly();
        //             })
        //             ->only('');
        //     });
    }
}
