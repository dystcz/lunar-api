<?php

namespace Dystcz\LunarApi\Domain\Shipping\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;

class ShippingOptionResource extends JsonApiResource
{
    public function id(): string
    {
        return $this->resource->getId();
    }

    public function attributes($request): iterable
    {
        $data = $this->resource->toArray();

        return [
            'name' => $this->resource->getName(),
            'description' => $this->resource->getDescription(),
            'identifier' => $this->resource->getIdentifier(),
            'price' => $data['price'],
            'currency' => $data['currency'],
        ];
    }
}
