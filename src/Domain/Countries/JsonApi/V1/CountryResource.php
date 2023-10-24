<?php

namespace Dystcz\LunarApi\Domain\Countries\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Illuminate\Http\Request;

class CountryResource extends JsonApiResource
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
