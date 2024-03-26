<?php

namespace Dystcz\LunarApi\Domain\CartAddresses\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\CartAddressSchema;
use Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress;
use Dystcz\LunarApi\Domain\ShippingOptions\JsonApi\V1\AttachShippingOptionRequest;
use Dystcz\LunarApi\Domain\ShippingOptions\JsonApi\V1\DetachShippingOptionRequest;
use LaravelJsonApi\Core\Responses\DataResponse;

class CartAddressShippingOptionController extends Controller
{
    /*
    * Attach shipping option to cart shipping address.
    */
    public function attachShippingOption(
        CartAddressSchema $schema,
        AttachShippingOptionRequest $request,
        CartAddress $cartAddress,
    ): DataResponse {
        $this->authorize('update', $cartAddress);

        $cartAddress->update([
            'shipping_option' => $request->input('data.attributes.shipping_option'),
        ]);

        $model = $schema
            ->repository()
            ->queryOne($cartAddress)
            ->withRequest($request)
            ->first();

        return DataResponse::make($model)->didntCreate();
    }

    /*
    * Detach shipping option from cart shipping address.
    */
    public function detachShippingOption(
        CartAddressSchema $schema,
        DetachShippingOptionRequest $request,
        CartAddress $cartAddress,
    ): DataResponse {
        $this->authorize('update', $cartAddress);

        // Detach shipping option
        $cartAddress->update(['shipping_option' => null]);

        $model = $schema
            ->repository()
            ->queryOne($cartAddress)
            ->withRequest($request)
            ->first();

        return DataResponse::make($model)->didntCreate();
    }
}
