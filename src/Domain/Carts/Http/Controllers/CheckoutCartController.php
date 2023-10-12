<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Carts\Actions\CreateUserFromCart;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartRequest;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Illuminate\Support\Facades\URL;
use LaravelJsonApi\Contracts\Store\Store as StoreContract;
use LaravelJsonApi\Core\Responses\DataResponse;
use Lunar\Facades\CartSession;

class CheckoutCartController extends Controller
{
    /**
     * Checkout user's cart.
     */
    public function checkout(
        StoreContract $store,
        CartRequest $request,
        CreateUserFromCart $createUserFromCartAction
    ): DataResponse {
        // $this->authorize('viewAny', Cart::class);

        /** @var Cart $cart */
        $cart = CartSession::current();

        if ($request->validated('create_user', false)) {
            $createUserFromCartAction($cart);
        }

        /** @var Order $order */
        $order = $cart->createOrder();

        /** @var Order $model */
        $model = $store
            ->queryOne('orders', $order)
            ->first();

        CartSession::forget();

        return DataResponse::make($model)
            ->withLinks([
                'self.signed' => URL::signedRoute(
                    'v1.orders.show',
                    ['order' => $order->id],
                ),
                'create-payment-intent.signed' => URL::signedRoute(
                    'v1.orders.createPaymentIntent',
                    ['order' => $order->id],
                ),
                'check-order-payment-status.signed' => URL::signedRoute(
                    'v1.orders.checkOrderPaymentStatus',
                    ['order' => $order->id],
                ),
            ])
            ->didCreate();
    }
}
