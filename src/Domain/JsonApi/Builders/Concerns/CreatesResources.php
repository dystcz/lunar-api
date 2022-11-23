<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders\Concerns;

use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait CreatesResources
{
    public function toRelationships(): array
    {
        return collect($this->includes)
            ->mapWithKeys(function (string $includeBuilderClass, string $relationship) {
                // Relation instance.
                $relation = (new static::$model)->$relationship();

                // Collection or Model returned by the relationship.
                $related = (new static::$model)->$relationship;

                /** @var JsonApiResource $includeResource */
                $includeResource = $includeBuilderClass::$resource;

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
