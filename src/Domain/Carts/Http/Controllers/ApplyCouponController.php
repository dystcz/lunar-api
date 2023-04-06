<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Carts\Actions\ApplyCoupon;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\AddCouponToCartRequest;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use LaravelJsonApi\Core\Responses\DataResponse;
use Lunar\Facades\CartSession;

class ApplyCouponController extends Controller
{
    /**
     * Apply coupon.
     */
    public function applyCoupon(
        AddCouponToCartRequest $request,
        ApplyCoupon $applyCoupon,
    ): DataResponse {
        // $this->authorize('viewAny', Cart::class);

        /** @var Cart $cart */
        $cart = CartSession::manager();

        $cart = $applyCoupon($cart, $request->validated('coupon_code'));

        return DataResponse::make($cart)
            ->didntCreate();
    }
}
