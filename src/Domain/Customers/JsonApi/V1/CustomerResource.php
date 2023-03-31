<?php

namespace Dystcz\LunarApi\Domain\Customers\JsonApi\V1;

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Illuminate\Http\Request;

class CustomerResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  Request|null  $request
     */
    public function attributes($request): iterable
    {
        /** @var Customer $model */
        $model = $this->resource;

        return [
            'title' => $model->title,
            'first_name' => $model->first_name,
            'last_name' => $model->last_name,
            'company_name' => $model->company_name,
            'account_ref' => $model->account_ref,
            'vat_no' => $model->vat_no,
            ...ResourceManifest::for(static::class)->attributes()->toResourceArray($this),
        ];
    }

    /**
     * Get the resource's relationships.
     *
     * @param  Request|null  $request
     */
    public function relationships($request): iterable
    {
        /** @var Customer $model */
        $model = $this->resource;

        return [
            $this->relation('orders'),

            $this->relation('addresses'),

            $this->relation('users'),

            ...ResourceManifest::for(static::class)->relationships()->toResourceArray($this),
        ];
    }
}
