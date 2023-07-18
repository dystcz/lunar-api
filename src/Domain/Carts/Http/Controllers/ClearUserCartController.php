<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartSchema;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Contracts\Store\Store as StoreContract;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use Lunar\Facades\CartSession;

class ClearUserCartController extends Controller
{
    /**
     * Clear all items from user's cart.
     */
    public function clear(
        CartSchema $schema,
        Cart $cart,
        string $resourceType
    ): Responsable {
        $this->authorize('view', $cart);

        $cart = CartSession::use($cart);

        $cart->clear();

        $cart->fresh();

        $store = App::make(StoreContract::class);

        $request = ResourceQuery::queryOne(
            $resourceType = $resourceType
        );

        $model = $store
            ->queryOne($resourceType, $cart)
            ->withRequest($request)
            ->first();

        return DataResponse::make($model)->didCreate();
    }
}
