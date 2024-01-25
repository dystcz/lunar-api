<?php

namespace Dystcz\LunarApi\Domain\CartAddresses\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\CartAddressQuery;
use Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\CartAddressRequest;
use Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1\CartAddressSchema;
use Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Store;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Update;

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
        CartAddressQuery $query,
    ): DataResponse {
        // $this->authorize('create');

        $validatedRequest = $request->validated();

        $meta = [
            ...$validatedRequest['meta'] ?? [],
            'company_in' => $request->validated('company_in', null),
            'company_tin' => $request->validated('company_tin', null),
        ];

        $model = $schema
            ->repository()
            ->create()
            ->withRequest($query)
            ->store(
                array_merge(
                    $request->validated(),
                    ['meta' => $meta],
                ),
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
        CartAddressQuery $query,
        CartAddress $cartAddress,
    ): DataResponse {
        $this->authorize('update', $cartAddress);

        $validatedRequest = $request->validated();

        $meta = array_merge($cartAddress->meta?->toArray() ?? [], [
            ...$validatedRequest['meta'] ?? [],
            'company_in' => $request->validated('company_in', null),
            'company_tin' => $request->validated('company_tin', null),
        ]);

        $model = $schema
            ->repository()
            ->update($cartAddress)
            ->withRequest($query)
            ->store(
                array_merge(
                    $request->validated(),
                    ['meta' => $meta],
                ),
            );

        return DataResponse::make($model)
            ->didntCreate();
    }
}
