<?php

namespace Dystcz\LunarApi\Domain\Orders\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Orders\JsonApi\V1\OrderSchema;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Illuminate\Support\Facades\URL;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Update;
use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;

class OrdersController extends Controller
{
    use FetchOne;
    use Update;

    /**
     * Fetch zero to one JSON API resource by id.
     *
     * @return \Illuminate\Contracts\Support\Responsable|\Illuminate\Http\Response
     */
    public function show(OrderSchema $schema, ResourceQuery $request, Order $post): DataResponse
    {
        $order = $schema
            ->repository()
            ->queryOne($post)
            ->withRequest($request)
            ->first();

        return DataResponse::make($order)
            ->withLinks([
                'self.signed' => URL::signedRoute(
                    'v1.orders.show',
                    ['order' => $order->id],
                ),
                'create-payment-intent.signed' => URL::signedRoute(
                    'v1.orders.createPaymentIntent',
                    ['order' => $order->id],
                ),
                'mark-order-pending-payment.signed' => URL::signedRoute(
                    'v1.orders.markPendingPayment',
                    ['order' => $order->id],
                ),
                'mark-order-awaiting-payment.signed' => URL::signedRoute(
                    'v1.orders.markAwaitingPayment',
                    ['order' => $order->id],
                ),
                'check-order-payment-status.signed' => URL::signedRoute(
                    'v1.orders.checkOrderPaymentStatus',
                    ['order' => $order->id],
                ),
            ])
            ->didntCreate();
    }
}
