<?php

namespace Dystcz\LunarApi\Domain\CartAddresses\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\CartAddresses\Contracts\UpdateCartAddressCountryController as UpdateCartAddressCountryControllerContract;
use Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\CartAddressSchema;
use Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\UpdateCartAddressCountryRequest;
use Dystcz\LunarApi\Facades\LunarApi;
use LaravelJsonApi\Core\Responses\DataResponse;
use Lunar\Models\Contracts\CartAddress as CartAddressContract;
use Lunar\Models\Country;

class UpdateCartAddressCountryController extends Controller implements UpdateCartAddressCountryControllerContract
{
    public function updateCountry(
        CartAddressSchema $schema,
        UpdateCartAddressCountryRequest $request,
        CartAddressContract $cartAddress,
    ): DataResponse {
        $this->authorize('update', $cartAddress);

        $countryId = $request->input('data.relationships.country.data.id', 0);

        if (LunarApi::usesHashids()) {
            $countryId = (new (Country::modelClas()))->decodedRouteKey($countryId);
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
