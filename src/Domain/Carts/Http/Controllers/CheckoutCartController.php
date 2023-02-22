<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use LaravelJsonApi\Contracts\Routing\Route;
use LaravelJsonApi\Contracts\Store\Store as StoreContract;
use LaravelJsonApi\Core\Responses\DataResponse;
use Lunar\Facades\CartSession;

class CheckoutCartController extends Controller
{
    /**
     * Checkout user's cart.
     *
     * @param Route         $route
     * @param StoreContract $store
     * @return Responsable|Response
     */
    public function checkout(Route $route, StoreContract $store)
    {
        // $this->authorize('viewAny', Cart::class);

        /** @var Cart $cart */
        $cart = CartSession::manager();

        $order = $cart->createOrder();

        $model = $store
            ->queryOne('orders', $order)
            ->first();

        return DataResponse::make($model)
            ->didCreate();
    }
}
