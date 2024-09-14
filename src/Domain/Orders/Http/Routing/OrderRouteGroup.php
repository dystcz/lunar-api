<?php

namespace Dystcz\LunarApi\Domain\Orders\Http\Routing;

use Dystcz\LunarApi\Domain\Orders\Contracts\CheckOrderPaymentStatusController;
use Dystcz\LunarApi\Domain\Orders\Contracts\CreatePaymentIntentController;
use Dystcz\LunarApi\Domain\Orders\Contracts\MarkOrderAwaitingPaymentController;
use Dystcz\LunarApi\Domain\Orders\Contracts\MarkOrderPendingPaymentController;
use Dystcz\LunarApi\Domain\Orders\Contracts\OrdersController;
use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Routing\ActionRegistrar;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

class OrderRouteGroup extends RouteGroup
{
    /**
     * Register routes.
     */
    public function routes(): void
    {
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function (ResourceRegistrar $server) {
                $server->resource($this->getPrefix(), OrdersController::class)
                    ->relationships(function (Relationships $relationships) {
                        $relationships->hasMany('order-lines')->readOnly();
                        $relationships->hasMany('product-lines')->readOnly();
                        $relationships->hasMany('digital-lines')->readOnly();
                        $relationships->hasMany('physical-lines')->readOnly();
                        $relationships->hasMany('shipping-lines')->readOnly();
                        $relationships->hasMany('payment-lines')->readOnly();
                        $relationships->hasMany('transactions')->readOnly();
                    })
                    ->only('show', 'update');

                $server->resource($this->getPrefix(), CreatePaymentIntentController::class)
                    ->only('')
                    ->actions('-actions', function (ActionRegistrar $actions) {
                        $actions->withId()->post('create-payment-intent');
                    });

                $server->resource($this->getPrefix(), MarkOrderPendingPaymentController::class)
                    ->only('')
                    ->actions('-actions', function (ActionRegistrar $actions) {
                        $actions->withId()->patch('mark-pending-payment');
                    });

                $server->resource($this->getPrefix(), MarkOrderAwaitingPaymentController::class)
                    ->only('')
                    ->actions('-actions', function (ActionRegistrar $actions) {
                        $actions->withId()->patch('mark-awaiting-payment');
                    });

                $server->resource($this->getPrefix(), CheckOrderPaymentStatusController::class)
                    ->only('')
                    ->actions('-actions', function (ActionRegistrar $actions) {
                        $actions->withId()->get('check-order-payment-status');
                    });
            });
    }
}
