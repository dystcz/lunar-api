<?php

namespace Dystcz\LunarApi\Domain\Collections\Http\Resources;

use Dystcz\LunarApi\Domain\JsonApi\Builders\CollectionJsonApiBuilder;
use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Illuminate\Http\Request;

class CollectionResource extends JsonApiResource
{
    protected function toAttributes(Request $request): array
    {
        return [
            ...$this->attribute_data->keys()->mapWithKeys(function ($key) {
                return [$key => $this->attr($key)];
            }),
            ['products_count' => fn () => $this->products_count],
        ];
    }

    protected function toRelationships(Request $request): array
    {
        return app(CollectionJsonApiBuilder::class)->toRelationships($this->resource);
    }
}
