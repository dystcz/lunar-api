<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartRequest;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Contracts\Store\Store as StoreContract;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Store;
use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use Lunar\Facades\CartSession;

class CartController extends Controller
{
    use Store;
    use FetchOne;

    public function saving(?Cart $cart, CartRequest $request, $query): DataResponse
    {
        /** @var Cart $cart */
        $cart = CartSession::current();

        $meta = $cart->meta ?? [];

        /**
         * Set the IP address of the user so that we can authorize the user to view the cart.
         */
        $meta['auth_user_ip'] = request()->ip();

        $cart->meta = $meta;

        $resourceType = 'carts';

        $cart->save();

        $cart = $cart->fresh();

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
