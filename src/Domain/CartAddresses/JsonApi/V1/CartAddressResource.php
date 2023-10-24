<?php

namespace Dystcz\LunarApi\Domain\CartAddresses\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Illuminate\Http\Request;

class CartAddressResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  Request|null  $request
     */
    public function attributes($request): iterable
    {
        return parent::attributes($request);
    }
}
