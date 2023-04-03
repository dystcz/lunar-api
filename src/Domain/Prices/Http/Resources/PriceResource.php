<?php

namespace Dystcz\LunarApi\Domain\Prices\Http\Resources;

use Dystcz\LunarApi\Domain\JsonApi\Builders\PriceJsonApiBuilder;
use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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
        return App::get(PriceJsonApiBuilder::class)->toRelationships($this->resource);
    }
}
