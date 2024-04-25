<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Contracts\Routing\Route;
use LaravelJsonApi\Contracts\Store\Store as StoreContract;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use Lunar\Base\CartSessionInterface;

class ReadUserCartController extends Controller
{
    /**
     * @var \Lunar\Managers\CartSessionManager
     */
    private CartSessionInterface $cartSession;

    public function __construct()
    {
        $this->cartSession = App::make(CartSessionInterface::class);
    }

    /**
     * Read user's cart.
     *
     * @return Responsable|Response
     */
    public function myCart(Route $route, StoreContract $store): DataResponse
    {
        $this->authorize('viewAny', Cart::class);

        /** @var Cart $cart */
        $cart = $this->cartSession->current();

        // If cart auto creation is disabled and no cart is found
        if (! $cart) {
            return DataResponse::make(null)
                ->didntCreate();
        }

        $request = ResourceQuery::queryOne(
            $resourceType = $route->resourceType()
        );

        $model = $store
            ->queryOne($resourceType, $cart->fresh())
            ->withRequest($request)
            ->first();

        return DataResponse::make($model)
            ->withQueryParameters($request)
            ->didntCreate();
    }
}
