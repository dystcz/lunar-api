<?php

namespace Dystcz\LunarApi\Domain\CollectionGroups\JsonApi\V1;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use Lunar\Models\CollectionGroup;

class CollectionGroupResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param \Illuminate\Http\Request|null $request
     * @return iterable
     */
    public function attributes($request): iterable
    {
        /** @var CollectionGroup */
        $model = $this->resource;

        return [
            'name' => $model->name,
        ];
    }

    /**
     * Get the resource's relationships.
     *
     * @param \Illuminate\Http\Request|null $request
     * @return iterable
     */
    public function relationships($request): iterable
    {
        return [];
    }
}
