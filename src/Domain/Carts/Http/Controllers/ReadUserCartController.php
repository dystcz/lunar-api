<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use LaravelJsonApi\Contracts\Routing\Route;
use LaravelJsonApi\Contracts\Store\Store as StoreContract;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use Lunar\Facades\CartSession;

class ReadUserCartController extends Controller
{
    /**
     * Read user's cart.
     *
     * @return Responsable|Response
     */
    public function myCart(Route $route, StoreContract $store)
    {
        // $this->authorize('viewAny', Cart::class);

        /** @var Cart $cart */
        $cart = CartSession::current();

        $request = ResourceQuery::queryOne(
            $resourceType = $route->resourceType()
        );

        $model = $store
            ->queryOne($resourceType, $cart->fresh())
            ->withRequest($request)
            ->first();

        return DataResponse::make($model)->withQueryParameters($request);
    }
}
