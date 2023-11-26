<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Carts\Actions\CreateUserFromCart;
use Dystcz\LunarApi\Domain\Carts\Contracts\CheckoutCart;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartRequest;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use LaravelJsonApi\Contracts\Store\Store as StoreContract;
use LaravelJsonApi\Core\Responses\DataResponse;
use Lunar\Base\CartSessionInterface;
use Lunar\Managers\CartSessionManager;

class CheckoutCartController extends Controller
{
    /**
     * @var CartSessionManager
     */
    private CartSessionInterface $cartSession;

    public function __construct()
    {
        $this->cartSession = App::make(CartSessionInterface::class);
    }

    /**
     * Checkout user's cart.
     */
    public function checkout(
        StoreContract $store,
        CartRequest $request,
        CreateUserFromCart $createUserFromCartAction,
        CheckoutCart $checkoutCartAction
    ): DataResponse {
        // $this->authorize('update', Cart::class);

        /** @var Cart $cart */
        $cart = $this->cartSession->current();

        if ($request->validated('create_user', false)) {
            $createUserFromCartAction($cart);
        }

        /** @var Order $order */
        $order = ($checkoutCartAction)($cart);

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
