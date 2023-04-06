<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartAddressSchema;
use Dystcz\LunarApi\Domain\Carts\Models\CartAddress;
use Illuminate\Http\Request;
use LaravelJsonApi\Core\Responses\DataResponse;

class DetachShippingOptionController extends Controller
{
    public function detachShippingOption(
        CartAddressSchema $schema,
        Request $request,
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

        return DataResponse::make($model)
                ->didntCreate();
    }
}
