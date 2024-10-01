<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\PaymentOptions\Entities\PaymentOption;

class PaymentOptionResource extends JsonApiResource
{
    /**
     * {@inheritDoc}
     */
    public function id(): string
    {
        return $this->resource->getId();
    }

    /**
     * {@inheritDoc}
     */
    public function attributes($request): iterable
    {
        /** @var PaymentOption $option */
        $option = $this->resource;

        return $option->toArray();
    }
}
