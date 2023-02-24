<?php

namespace Dystcz\LunarApi\Domain\Shipping\JsonApi\V1;

use LaravelJsonApi\Core\Resources\JsonApiResource;

class ShippingOptionResource extends JsonApiResource
{
    public function id(): string
    {
        return $this->resource->getId();
    }

    public function attributes($request): iterable
    {
        return [
            'name' => $this->resource->getName(),
            'description' => $this->resource->getDescription(),
            'identifier' => $this->resource->getIdentifier(),
            'price' => $this->resource->getPrice(),
        ];
    }
}