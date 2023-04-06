<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartAddressSchema;
use Dystcz\LunarApi\Domain\Carts\Models\CartAddress;
use Dystcz\LunarApi\Domain\Shipping\JsonApi\V1\AttachShippingOptionRequest;
use LaravelJsonApi\Core\Responses\DataResponse;

class AttachShippingOptionController extends Controller
{
    public function attachShippingOption(
        CartAddressSchema $schema,
        AttachShippingOptionRequest $request,
        CartAddress $cartAddress,
    ): DataResponse {
        $this->authorize('update', $cartAddress);

        ray($request->input('data.attributes.shipping_option'));
        $cartAddress->update([
            'shipping_option' => $request->input('data.attributes.shipping_option'),
        ]);

        $model = $schema
            ->repository()
            ->queryOne($cartAddress)
            ->withRequest($request)
            ->first();

        return DataResponse::make($model)
                ->didntCreate();
    }
}
