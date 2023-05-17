<?php

namespace Dystcz\LunarApi\Domain\Orders\Http\Routing;

use Dystcz\LunarApi\Domain\Orders\Http\Controllers\CreatePaymentIntentController;
use Dystcz\LunarApi\Domain\Orders\Http\Controllers\OrdersController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class OrderRouteGroup extends RouteGroup
{
    public array $middleware = [];

    /**
     * Register routes.
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function ($server) {
                $server->resource($this->getPrefix(), OrdersController::class)
                    ->relationships(function ($relationships) {
                        $relationships->hasMany('lines')->readOnly();
                    })
                    ->only('show', 'update');

                $server->resource($this->getPrefix(), CreatePaymentIntentController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->withId()->post('create-payment-intent');
                    });
            });
    }
}
