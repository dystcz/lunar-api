<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

use Dystcz\LunarApi\Domain\JsonApi\Builders\Elements\IncludeElement;
use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait CreatesResources
{
    public function toRelationships(Model $model): array
    {
        return collect($this->includes())
            ->mapWithKeys(function (IncludeElement $includeElement) use ($model) {
                $relationship = $includeElement->getName();

                // Relation instance.
                $relation = $model->$relationship();

                // Collection or Model returned by the relationship.
                $related = $model->$relationship;

                /** @var JsonApiResource $includeResource */
                $includeResource = $includeElement->getBuilderClass()::$resource;

                return [
                    $relationship => fn () => optional(
                        $related,
                        fn (Model|Collection $related) => $this->isCollectionRelationship($relation)
                            ? $includeResource::collection($related)
                            : $includeResource::make($related)
                    ),
                ];
            })
            ->all();
    }
}
