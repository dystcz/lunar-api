<?php

namespace Dystcz\LunarApi\Domain\CartAddresses\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\CartAddresses\Contracts\CartAddressShippingOptionController as CartAddressShippingOptionControllerContract;
use Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\CartAddressSchema;
use Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress;
use Dystcz\LunarApi\Domain\ShippingOptions\JsonApi\V1\SetShippingOptionRequest;
use Dystcz\LunarApi\Domain\ShippingOptions\JsonApi\V1\UnsetShippingOptionRequest;
use LaravelJsonApi\Core\Responses\DataResponse;

class CartAddressShippingOptionController extends Controller implements CartAddressShippingOptionControllerContract
{
    /*
    * Set shipping option to cart shipping address.
    */
    public function setShippingOption(
        CartAddressSchema $schema,
        SetShippingOptionRequest $request,
        CartAddress $cartAddress,
    ): DataResponse {
        $this->authorize('update', $cartAddress);

        // Set shipping option
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
    * Unset shipping option from cart shipping address.
    */
    public function unsetShippingOption(
        CartAddressSchema $schema,
        UnsetShippingOptionRequest $request,
        CartAddress $cartAddress,
    ): DataResponse {
        $this->authorize('update', $cartAddress);

        // Unset shipping option
        $cartAddress->update(['shipping_option' => null]);

        $model = $schema
            ->repository()
            ->queryOne($cartAddress)
            ->withRequest($request)
            ->first();

        return DataResponse::make($model)->didntCreate();
    }
}
