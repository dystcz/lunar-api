<?php

namespace Dystcz\LunarApi\Domain\Collections\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Lunar\Models\Collection;

class CollectionResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  \Illuminate\Http\Request|null  $request
     * @return iterable
     */
    public function attributes($request): iterable
    {
        /** @var Collection $model */
        $model = $this->resource;

        return array_merge(
            $model->attribute_data->mapWithKeys(fn ($value, $field) => [
                $field => $model->translateAttribute($field),
            ])->toArray(),
            [],
            ResourceManifest::for(static::class)->attributes()->toResourceArray($this),
        );
    }

    /**
     * Get the resource's relationships.
     *
     * @param  \Illuminate\Http\Request|null  $request
     * @return iterable
     */
    public function relationships($request): iterable
    {
        /** @var Collection */
        $model = $this->resource;

        return [
            $this->relation('default_url'),

            $this
                ->relation('group')
                ->withoutLinks(),

            $this
                ->relation('products')
                ->withMeta(array_filter([
                    'count' => $model->products_count,
                ], fn ($value) => null !== $value)),
            $this
                ->relation('urls')
                ->withoutLinks(),
            ...ResourceManifest::for(static::class)->relationships()->toResourceArray($this),
        ];
    }
}
