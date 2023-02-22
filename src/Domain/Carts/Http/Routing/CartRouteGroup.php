<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Routing;

use Dystcz\LunarApi\Domain\Carts\Http\Controllers\CheckoutCartController;
use Dystcz\LunarApi\Domain\Carts\Http\Controllers\ClearUserCartController;
use Dystcz\LunarApi\Domain\Carts\Http\Controllers\ReadUserCartController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class CartRouteGroup extends RouteGroup
{
    /** @var string */
    public string $prefix = 'carts';

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
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $server->resource($this->getPrefix(), ClearUserCartController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->delete('clear');
                    });

                $server->resource($this->getPrefix(), ReadUserCartController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->get('my-cart');
                    });

                $server->resource($this->getPrefix(), CheckoutCartController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->post('checkout');
                    });
            });
    }
}
