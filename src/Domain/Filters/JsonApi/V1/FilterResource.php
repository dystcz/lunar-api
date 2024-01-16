<?php

namespace Dystcz\LunarApi\Domain\Filters\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;

class FilterResource extends JsonApiResource
{
    /**
     * Get the resource id.
     */
    public function id(): string
    {
        return $this->resource->getHandle();
    }

    /**
     * Get the resource's attributes.
     *
     * @param  Request|null  $request
     */
    public function attributes($request): iterable
    {
        $data = $this->resource->toArray();

        return [
            'handle' => $this->resource->getHandle(),
            'data_type' => $this->resource->getDataType(),
            'name' => $this->resource->getName(),
            'position' => $this->resource->getPosition(),
            'options' => $this->resource->getOptions(),
            'meta' => $this->resource->getMeta(),
        ];
    }
}
