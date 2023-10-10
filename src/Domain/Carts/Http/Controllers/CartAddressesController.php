<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartAddressRequest;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartAddressSchema;
use Dystcz\LunarApi\Domain\Carts\Models\CartAddress;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Store;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Update;
use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;

class CartAddressesController extends Controller
{
    use Store;
    use Update;

    /**
     * Create a new resource.
     *
     * @return \Illuminate\Contracts\Support\Responsable|\Illuminate\Http\Response
     */
    public function store(
        CartAddressSchema $schema,
        CartAddressRequest $request,
        ResourceQuery $query,
    ) {
        $this->authorize('create');

        $meta = [
            'company_in' => $request->validated('company_in', null),
            'company_tin' => $request->validated('company_tin', null),
        ];

        $model = $schema
            ->repository()
            ->create()
            ->withRequest($query)
            ->store(
                array_merge($request->validated(), ['meta' => $meta]),
            );

        return DataResponse::make($model)
            ->didCreate();
    }

    /**
     * Update an existing resource.
     *
     * @return \Illuminate\Contracts\Support\Responsable|\Illuminate\Http\Response
     */
    public function update(
        CartAddressSchema $schema,
        CartAddressRequest $request,
        ResourceQuery $query,
        CartAddress $cartAddress,
    ): DataResponse {
        $this->authorize('update', $cartAddress);

        $meta = array_merge($cartAddress->meta?->toArray() ?? [], [
            'company_in' => $request->validated('company_in', null),
            'company_tin' => $request->validated('company_tin', null),
        ]);

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
