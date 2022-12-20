<?php

namespace Dystcz\LunarApi\Domain\Collections\JsonApi\V1;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use Lunar\Models\Collection;

class CollectionResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param \Illuminate\Http\Request|null $request
     * @return iterable
     */
    public function attributes($request): iterable
    {
        /** @var Collection */
        $model = $this->resource;

        return array_merge(
            $model->attribute_data->mapWithKeys(fn ($value, $field) => [
                $field => $model->translateAttribute($field),
            ])->toArray(),
            []
        );
    }

    /**
     * Get the resource's relationships.
     *
     * @param \Illuminate\Http\Request|null $request
     * @return iterable
     */
    public function relationships($request): iterable
    {
        /** @var Collection */
        $model = $this->resource;

        return [
            $this->relation('urls'),
            $this->relation('default_url'),
            $this->relation('group'),
            $this
                ->relation('products')
                ->withMeta(array_filter([
                    'count' => $model->products_count,
                ], fn ($value) => null !== $value)),
        ];
    }
}
