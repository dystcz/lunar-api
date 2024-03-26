<?php

namespace Dystcz\LunarApi\Domain\ShippingOptions\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\ShippingOptions\Entities\ShippingOption;

class ShippingOptionResource extends JsonApiResource
{
    public function id(): string
    {
        return $this->resource->getId();
    }

    public function attributes($request): iterable
    {
        /** @var ShippingOption $option */
        $option = $this->resource;

        return $option->toArray();
    }
}
