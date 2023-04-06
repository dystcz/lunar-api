<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Carts\Actions\CreateUserFromCart;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartRequest;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
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
        $cart = CartSession::manager();

        if ($request->validated('create_user') ?? false) {
            $createUserFromCartAction($cart);
        }

        $order = $cart->createOrder();

        $model = $store
            ->queryOne('orders', $order)
            ->first();

        return DataResponse::make($model)
            ->withLinks([
                'self.signed' => URL::temporarySignedRoute(
                    'v1.orders.show', now()->addDays(28), ['order' => $order->id]
                ),
            ])
            ->didCreate();
    }
}
