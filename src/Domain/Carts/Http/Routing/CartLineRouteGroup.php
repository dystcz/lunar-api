<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Routing;

use Dystcz\LunarApi\Domain\Carts\Http\Controllers\CartLinesController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class CartLineRouteGroup extends RouteGroup
{
    /** @var string */
    public string $prefix = 'cart-lines';

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
                $server->resource($this->getPrefix(), CartLinesController::class)
                    ->only('update', 'destroy', 'store');
            });
    }
}
