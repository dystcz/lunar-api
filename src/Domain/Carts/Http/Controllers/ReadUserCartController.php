<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Carts\Contracts\CurrentSessionCart;
use Dystcz\LunarApi\Domain\Carts\Contracts\ReadUserCartController as ReadUserCartControllerContract;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartQuery;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartSchema;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use LaravelJsonApi\Core\Responses\DataResponse;
use Lunar\Models\Cart;

class ReadUserCartController extends Controller implements ReadUserCartControllerContract
{
    /**
     * Read user's cart.
     *
     * @return Responsable|Response
     */
    public function myCart(CartSchema $schema, CartQuery $query, ?CurrentSessionCart $cart): DataResponse
    {
        $this->authorize('viewAny', Cart::modelClass());

        // If cart auto creation is disabled and no cart is found
        if (! $cart) {
            return DataResponse::make(null)
                ->didntCreate();
        }

        return DataResponse::make($cart)
            ->withQueryParameters($query)
            ->didntCreate();
    }
}
