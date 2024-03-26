<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\AttachPaymentOptionRequest;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartSchema;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\DetachPaymentOptionRequest;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
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
     * Attach payment option to cart.
     */
    public function attachPaymentOption(
        CartSchema $schema,
        AttachPaymentOptionRequest $request,
    ): DataResponse {
        /** @var Cart $cart */
        $cart = $this->cartSession->current();

        $this->authorize('update', $cart);

        $model = $cart->setPaymentOption($request->input('data.attributes.payment_option'));

        return DataResponse::make($model)
            ->didntCreate();
    }

    /**
     * Detach payment option from cart.
     */
    public function detachPaymentOption(
        CartSchema $schema,
        DetachPaymentOptionRequest $request,
        Cart $cart,
    ): DataResponse {
        $this->authorize('update', $cart);

        $model = $cart->unsetPaymentOption($request->input('data.attributes.payment_option'));

        return DataResponse::make($model)
            ->didntCreate();
    }
}
