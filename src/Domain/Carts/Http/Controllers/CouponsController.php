<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Carts\Actions\SetCoupon;
use Dystcz\LunarApi\Domain\Carts\Actions\UnsetCoupon;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\SetCouponToCartRequest;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Core\Responses\DataResponse;
use Lunar\Base\CartSessionInterface;
use Lunar\Managers\CartSessionManager;

class CouponsController extends Controller
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
        SetCoupon $applyCoupon,
    ): DataResponse {
        /** @var Cart $cart */
        $cart = $this->cartSession->current();

        $this->authorize('updateCoupon', $cart);

        $cart = $applyCoupon($cart, $request->validated('coupon_code'));

        return DataResponse::make($cart)
            ->didntCreate();
    }

    /**
     * Unset coupon from cart.
     */
    public function unsetCoupon(
        UnsetCoupon $removeCoupon,
    ): DataResponse {
        /** @var Cart $cart */
        $cart = $this->cartSession->current();

        $this->authorize('updateCoupon', $cart);

        $cart = $removeCoupon($cart);

        return DataResponse::make($cart)
            ->didntCreate();
    }
}
