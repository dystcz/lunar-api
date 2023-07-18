<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Routing;

use Dystcz\LunarApi\Domain\Carts\Http\Controllers\ApplyCouponController;
use Dystcz\LunarApi\Domain\Carts\Http\Controllers\CartController;
use Dystcz\LunarApi\Domain\Carts\Http\Controllers\CheckoutCartController;
use Dystcz\LunarApi\Domain\Carts\Http\Controllers\ClearUserCartController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class CartRouteGroup extends RouteGroup
{
    public array $middleware = [];

    /**
     * Register routes.
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $server
                    ->resource($this->getPrefix(), CartController::class)
                    ->only('show', 'store');

                $server->resource($this->getPrefix(), ClearUserCartController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->withId()->delete('clear');
                    });

                $server->resource($this->getPrefix(), CheckoutCartController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->withId()->post('checkout');
                    });

                $server->resource($this->getPrefix(), ApplyCouponController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->withId()->post('apply-coupon');
                    });
            });
    }
}
