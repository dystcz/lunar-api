<?php

namespace Dystcz\LunarApi\Domain\Collections\Http\Resources;

use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\Media\Http\Resources\MediaResource;
use Dystcz\LunarApi\Domain\Products\Http\Resources\ProductResource;
use Dystcz\LunarApi\Domain\Products\Http\Resources\ProductVariantIndexResource;
use Dystcz\LunarApi\Domain\Urls\Http\Resources\UrlResource;
use Illuminate\Http\Request;

class CollectionResource extends JsonApiResource
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
            'products' => $this->optionalCollection(ProductResource::class, 'products'),
            'defaultUrl' => $this->optionalResource(UrlResource::class, 'defaultUrl'),
            'thumbnail' => $this->optionalResource(MediaResource::class, 'thumbnail'),
        ];
    }
}
