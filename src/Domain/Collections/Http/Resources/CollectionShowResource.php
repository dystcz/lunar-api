<?php

namespace Dystcz\LunarApi\Domain\Collections\Http\Resources;

use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\Media\Http\Resources\MediaResource;
use Dystcz\LunarApi\Domain\Products\Http\Resources\ProductIndexResource;
use Dystcz\LunarApi\Domain\Urls\Http\Resources\UrlResource;
use Illuminate\Http\Request;

class CollectionShowResource extends JsonApiResource
{
    protected function toAttributes(Request $request): array
    {
        return [
            ...$this->attribute_data->keys()->mapWithKeys(function ($key) {
                return [$key => $this->attr($key)];
            }),
        ];
    }

    protected function toRelationships(Request $request): array
    {
        return [
            'thumbnail' => $this->optionalResource(MediaResource::class, 'thumbnail'),
            'defaultUrl' => $this->optionalResource(UrlResource::class, 'defaultUrl'),
            'products' => $this->optionalCollection(ProductIndexResource::class, 'products'),
        ];
    }
}
