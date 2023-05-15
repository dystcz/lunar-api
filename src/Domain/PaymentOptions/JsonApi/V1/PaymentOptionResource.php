<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;

class PaymentOptionResource extends JsonApiResource
{
    public function id(): string
    {
        return $this->resource->getId();
    }

    public function attributes($request): iterable
    {
        return [
            'name' => $this->resource->getName(),
            'driver' => $this->resource->getDriver(),
        ];
    }
}
