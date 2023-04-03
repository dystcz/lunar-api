<?php

namespace Dystcz\LunarApi\Domain\Urls\Http\Resources;

use Illuminate\Support\Facades\App;
use Dystcz\LunarApi\Domain\JsonApi\Builders\UrlJsonApiBuilder;
use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Illuminate\Http\Request;

class UrlResource extends JsonApiResource
{
    protected function toAttributes(Request $request): array
    {
        return [
            'slug' => $this->slug,
        ];
    }

    protected function toRelationships(Request $request): array
    {
        return App::get(UrlJsonApiBuilder::class)->toRelationships($this->resource);
    }
}
