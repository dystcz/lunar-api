<?php

namespace Dystcz\LunarApi\Domain\Prices\Http\Resources;

use Dystcz\LunarApi\Domain\JsonApi\Builders\PriceJsonApiBuilder;
use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Illuminate\Http\Request;

class PriceResource extends JsonApiResource
{
    protected function toAttributes(Request $request): array
    {
        return [
            'price' => $this->price->decimal,
        ];
    }

    protected function toRelationships(Request $request): array
    {
        return app(PriceJsonApiBuilder::class)->toRelationships();
    }
}
