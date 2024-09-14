<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Carts\Contracts\CartPaymentOptionController as CartPaymentOptionControllerContract;
use Dystcz\LunarApi\Domain\Carts\Contracts\CurrentSessionCart;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\SetPaymentOptionRequest;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\UnsetPaymentOptionRequest;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\PaymentOptions\Facades\PaymentManifest;
use LaravelJsonApi\Core\Responses\DataResponse;

class CartPaymentOptionController extends Controller implements CartPaymentOptionControllerContract
{
    /**
     * Set payment option to cart.
     */
    public function setPaymentOption(
        SetPaymentOptionRequest $request,
        ?CurrentSessionCart $cart,
    ): DataResponse {
        /** @var Cart $cart */
        $this->authorize('updatePaymentOption', $cart);

        $option = PaymentManifest::getOption($cart, $request->input('data.attributes.payment_option'));

        $model = $cart->setPaymentOption($option);

        return DataResponse::make($model)
            ->didntCreate();
    }

    /**
     * Unset payment option from cart.
     */
    public function unsetPaymentOption(
        UnsetPaymentOptionRequest $request,
        ?CurrentSessionCart $cart,
    ): DataResponse {
        /** @var Cart $cart */
        $this->authorize('updatePaymentOption', $cart);

        $model = $cart->unsetPaymentOption();

        return DataResponse::make($model)
            ->didntCreate();
    }
}
