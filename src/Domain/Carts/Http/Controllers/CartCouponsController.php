<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Carts\Actions\SetCoupon;
use Dystcz\LunarApi\Domain\Carts\Actions\UnsetCoupon;
use Dystcz\LunarApi\Domain\Carts\Contracts\CartCouponsController as CartCouponsControllerContract;
use Dystcz\LunarApi\Domain\Carts\Contracts\CurrentSessionCart;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\SetCouponToCartRequest;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use LaravelJsonApi\Core\Responses\DataResponse;

class CartCouponsController extends Controller implements CartCouponsControllerContract
{
    /**
     * Set coupon to cart.
     */
    public function setCoupon(
        SetCouponToCartRequest $request,
        SetCoupon $setCoupon,
        ?CurrentSessionCart $cart
    ): DataResponse {
        /** @var Cart $cart */
        $this->authorize('updateCoupon', $cart);

        $cart = $setCoupon($cart, $request->validated('coupon_code'));

        return DataResponse::make($cart)
            ->didntCreate();
    }

    /**
     * Unset coupon from cart.
     */
    public function unsetCoupon(
        UnsetCoupon $unsetCoupon,
        ?CurrentSessionCart $cart
    ): DataResponse {
        /** @var Cart $cart */
        $this->authorize('updateCoupon', $cart);

        $cart = $unsetCoupon($cart);

        return DataResponse::make($cart)
            ->didntCreate();
    }
}
