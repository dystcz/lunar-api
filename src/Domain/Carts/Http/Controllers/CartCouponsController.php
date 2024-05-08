<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Carts\Actions\SetCoupon;
use Dystcz\LunarApi\Domain\Carts\Actions\UnsetCoupon;
use Dystcz\LunarApi\Domain\Carts\Contracts\CartCouponsController as CartCouponsControllerContract;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\SetCouponToCartRequest;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Core\Responses\DataResponse;
use Lunar\Base\CartSessionInterface;
use Lunar\Managers\CartSessionManager;

class CartCouponsController extends Controller implements CartCouponsControllerContract
{
    protected CartSessionManager $cartSession;

    public function __construct(
    ) {
        $this->cartSession = App::make(CartSessionInterface::class);
    }

    /**
     * Set coupon to cart.
     */
    public function setCoupon(
        SetCouponToCartRequest $request,
        SetCoupon $setCoupon,
    ): DataResponse {
        /** @var Cart $cart */
        $cart = $this->cartSession->current();

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
    ): DataResponse {
        /** @var Cart $cart */
        $cart = $this->cartSession->current();

        $this->authorize('updateCoupon', $cart);

        $cart = $unsetCoupon($cart);

        return DataResponse::make($cart)
            ->didntCreate();
    }
}
