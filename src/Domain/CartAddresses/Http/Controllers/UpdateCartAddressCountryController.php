<?php

namespace Dystcz\LunarApi\Domain\CartAddresses\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\CartAddresses\Contracts\UpdateCartAddressCountryController as UpdateCartAddressCountryControllerContract;
use Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\CartAddressSchema;
use Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\UpdateCartAddressCountryRequest;
use Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress;
use Dystcz\LunarApi\Domain\Countries\Models\Country;
use Dystcz\LunarApi\LunarApi;
use LaravelJsonApi\Core\Responses\DataResponse;

class UpdateCartAddressCountryController extends Controller implements UpdateCartAddressCountryControllerContract
{
    public function updateCountry(
        CartAddressSchema $schema,
        UpdateCartAddressCountryRequest $request,
        CartAddress $cartAddress,
    ): DataResponse {
        $this->authorize('update', $cartAddress);

        $countryId = $request->input('data.relationships.country.data.id', 0);

        if (LunarApi::usesHashids()) {
            $countryId = (new Country)->decodedRouteKey($countryId);
        }

        $cartAddress->update([
            'country_id' => $countryId,
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
