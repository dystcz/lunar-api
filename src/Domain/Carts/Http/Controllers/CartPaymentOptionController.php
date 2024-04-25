<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\SetPaymentOptionRequest;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\UnsetPaymentOptionRequest;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\PaymentOptions\Facades\PaymentManifest;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Core\Responses\DataResponse;
use Lunar\Base\CartSessionInterface;

class CartPaymentOptionController extends Controller
{
    /**
     * @var CartSessionManager
     */
    private CartSessionInterface $cartSession;

    public function __construct()
    {
        $this->cartSession = App::make(CartSessionInterface::class);
    }

    /**
     * Set payment option to cart.
     */
    public function setPaymentOption(
        SetPaymentOptionRequest $request,
    ): DataResponse {
        /** @var Cart $cart */
        $cart = $this->cartSession->current();

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
    ): DataResponse {
        /** @var Cart $cart */
        $cart = $this->cartSession->current();

        $this->authorize('updatePaymentOption', $cart);

        $model = $cart->unsetPaymentOption();

        return DataResponse::make($model)
            ->didntCreate();
    }
}
