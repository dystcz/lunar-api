<?php

namespace Dystcz\LunarApi\Domain\CartAddresses\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\CartAddresses\Contracts\ContinuousUpdateCartAddressController as ContinuousUpdateCartAddressControllerContract;
use Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\CartAddressContinuousUpdateRequest;
use Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\CartAddressQuery;
use Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\CartAddressSchema;
use LaravelJsonApi\Core\Responses\DataResponse;
use Lunar\Models\Contracts\CartAddress as CartAddressContract;

class ContinuousUpdateCartAddressController extends Controller implements ContinuousUpdateCartAddressControllerContract
{
    /**
     * Update an existing resource.
     *
     * @return \Illuminate\Contracts\Support\Responsable|\Illuminate\Http\Response
     */
    public function continuousUpdate(
        CartAddressSchema $schema,
        CartAddressContinuousUpdateRequest $request,
        CartAddressQuery $query,
        CartAddressContract $cartAddress,
    ): DataResponse {
        $this->authorize('update', $cartAddress);

        $meta = array_merge(
            $cartAddress->meta?->toArray() ?? [],
            [
                'company_in' => $request->validated('company_in', null),
                'company_tin' => $request->validated('company_tin', null),
            ],
        );

        $model = $schema
            ->repository()
            ->update($cartAddress)
            ->withRequest($query)
            ->store(
                array_merge($request->validated(), ['meta' => $meta]),
            );

        return DataResponse::make($model)
            ->didntCreate();
    }
}
