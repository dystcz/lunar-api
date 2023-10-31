<?php

namespace Dystcz\LunarApi\Domain\Addresses\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Addresses\JsonApi\V1\AddressQuery;
use Dystcz\LunarApi\Domain\Addresses\JsonApi\V1\AddressRequest;
use Dystcz\LunarApi\Domain\Addresses\JsonApi\V1\AddressSchema;
use Dystcz\LunarApi\Domain\Addresses\Models\Address;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Destroy;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Store;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Update;

class AddressesController extends Controller
{
    use Destroy;
    use FetchMany;
    use FetchOne;
    use Store;
    use Update;

    /**
     * Create a new resource.
     *
     * @return \Illuminate\Contracts\Support\Responsable|\Illuminate\Http\Response
     */
    public function store(
        AddressSchema $schema,
        AddressRequest $request,
        AddressQuery $query,
    ): DataResponse {
        // $this->authorize('create');

        $meta = [
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
        AddressSchema $schema,
        AddressRequest $request,
        AddressQuery $query,
        Address $address,
    ): DataResponse {
        $this->authorize('update', $address);

        $meta = array_merge($address->meta?->toArray() ?? [], [
            'company_in' => $request->validated('company_in', null),
            'company_tin' => $request->validated('company_tin', null),
        ]);

        $model = $schema
            ->repository()
            ->update($address)
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
