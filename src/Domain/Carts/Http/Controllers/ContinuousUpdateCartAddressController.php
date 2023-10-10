<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartAddressContinuousUpdateRequest;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartAddressSchema;
use Dystcz\LunarApi\Domain\Carts\Models\CartAddress;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;

class ContinuousUpdateCartAddressController extends Controller
{
    /**
     * Update an existing resource.
     *
     * @return \Illuminate\Contracts\Support\Responsable|\Illuminate\Http\Response
     */
    public function continuousUpdate(
        CartAddressSchema $schema,
        CartAddressContinuousUpdateRequest $request,
        ResourceQuery $query,
        CartAddress $cartAddress,
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
