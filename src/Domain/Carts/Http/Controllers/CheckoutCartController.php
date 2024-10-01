<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Carts\Actions\CreateUserFromCart;
use Dystcz\LunarApi\Domain\Carts\Contracts\CheckoutCart;
use Dystcz\LunarApi\Domain\Carts\Contracts\CheckoutCartController as CheckoutCartControllerContract;
use Dystcz\LunarApi\Domain\Carts\Contracts\CurrentSessionCart;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CheckoutCartRequest;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Illuminate\Support\Facades\URL;
use LaravelJsonApi\Contracts\Store\Store as StoreContract;
use LaravelJsonApi\Core\Responses\DataResponse;

class CheckoutCartController extends Controller implements CheckoutCartControllerContract
{
    /**
     * Checkout user's cart.
     */
    public function checkout(
        StoreContract $store,
        CheckoutCartRequest $request,
        CreateUserFromCart $createUserFromCartAction,
        CheckoutCart $checkoutCartAction,
        ?CurrentSessionCart $cart
    ): DataResponse {
        /** @var Cart $cart */
        $this->authorize('checkout', $cart);

        if ($request->validated('create_user', false)) {
            $createUserFromCartAction($cart);
        }

        /** @var Order $order */
        $order = ($checkoutCartAction)($cart);

        // TODO: Refactor to default json:api links
        return DataResponse::make($order)
            ->withLinks([
                'self.signed' => URL::signedRoute(
                    'v1.orders.show',
                    ['order' => $order->getRouteKey()],
                ),
                'create-payment-intent.signed' => URL::signedRoute(
                    'v1.orders.createPaymentIntent',
                    ['order' => $order->getRouteKey()],
                ),
                'mark-order-pending-payment.signed' => URL::signedRoute(
                    'v1.orders.markPendingPayment',
                    ['order' => $order->getRouteKey()],
                ),
                'mark-order-awaiting-payment.signed' => URL::signedRoute(
                    'v1.orders.markAwaitingPayment',
                    ['order' => $order->getRouteKey()],
                ),
                'check-order-payment-status.signed' => URL::signedRoute(
                    'v1.orders.checkOrderPaymentStatus',
                    ['order' => $order->getRouteKey()],
                ),
            ])
            ->didCreate();
    }
}
