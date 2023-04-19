<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartAddressSchema;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\UpdateCartAddressCountryRequest;
use Dystcz\LunarApi\Domain\Carts\Models\CartAddress;
use LaravelJsonApi\Core\Responses\DataResponse;

class UpdateCartAddressCountryController extends Controller
{
    public function updateCountry(
        CartAddressSchema $schema,
        UpdateCartAddressCountryRequest $request,
        CartAddress $cartAddress,
    ): DataResponse {
        $this->authorize('update', $cartAddress);

        $cartAddress->update([
            'country_id' => $request->input('data.relationships.country.data.id'),
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
