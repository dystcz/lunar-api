<?php

namespace Dystcz\LunarApi\Domain\Orders\Http\Routing;

use Dystcz\LunarApi\Domain\Orders\Http\Controllers\CheckOrderPaymentStatusController;
use Dystcz\LunarApi\Domain\Orders\Http\Controllers\CreatePaymentIntentController;
use Dystcz\LunarApi\Domain\Orders\Http\Controllers\MarkOrderAwaitingPaymentController;
use Dystcz\LunarApi\Domain\Orders\Http\Controllers\MarkOrderPendingPaymentController;
use Dystcz\LunarApi\Domain\Orders\Http\Controllers\OrdersController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class OrderRouteGroup extends RouteGroup
{
    public array $middleware = [];

    /**
     * Register routes.
     */
    public function routes(string $prefix = null, array|string $middleware = []): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function ($server) {
                $server->resource($this->getPrefix(), OrdersController::class)
                    ->relationships(function ($relationships) {
                        $relationships->hasMany('order_lines')->readOnly();
                        $relationships->hasMany('product_lines')->readOnly();
                        $relationships->hasMany('digital_lines')->readOnly();
                        $relationships->hasMany('physical_lines')->readOnly();
                        $relationships->hasMany('shipping_lines')->readOnly();
                        $relationships->hasMany('transactions')->readOnly();
                    })
                    ->only('show', 'update');

                $server->resource($this->getPrefix(), CreatePaymentIntentController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->withId()->post('create-payment-intent');
                    });

                $server->resource($this->getPrefix(), MarkOrderPendingPaymentController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->withId()->patch('mark-pending-payment');
                    });

                $server->resource($this->getPrefix(), MarkOrderAwaitingPaymentController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->withId()->patch('mark-awaiting-payment');
                    });

                $server->resource($this->getPrefix(), CheckOrderPaymentStatusController::class)
                    ->only('')
                    ->actions('-actions', function ($actions) {
                        $actions->withId()->get('check-order-payment-status');
                    });
            });
    }
}
