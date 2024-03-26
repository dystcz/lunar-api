<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Carts\Actions\ApplyCoupon;
use Dystcz\LunarApi\Domain\Carts\Actions\RemoveCoupon;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\AddCouponToCartRequest;
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
     * Apply coupon.
     */
    public function update(
        AddCouponToCartRequest $request,
        ApplyCoupon $applyCoupon,
    ): DataResponse {
        /** @var Cart $cart */
        $cart = $this->cartSession->current();

        $this->authorize('updateCoupon', $cart);

        $cart = $applyCoupon($cart, $request->validated('coupon_code'));

        return DataResponse::make($cart)
            ->didntCreate();
    }

    /**
     * Remove coupon.
     */
    public function destroy(
        RemoveCoupon $removeCoupon,
    ): DataResponse {
        /** @var Cart $cart */
        $cart = $this->cartSession->current();

        $this->authorize('updateCoupon', $cart);

        $cart = $removeCoupon($cart);

        return DataResponse::make($cart)
            ->didntCreate();
    }
}
