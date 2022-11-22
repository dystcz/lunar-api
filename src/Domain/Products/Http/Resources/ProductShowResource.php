<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Resources;

use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\Media\Http\Resources\MediaResource;
use Dystcz\LunarApi\Domain\Urls\Http\Resources\UrlResource;
use Illuminate\Http\Request;

class ProductShowResource extends JsonApiResource
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
            'variants' => $this->optionalCollection(ProductVariantResource::class, 'variants'),
            'thumbnail' => $this->optionalResource(MediaResource::class, 'thumbnail'),
            'defaultUrl' => $this->optionalResource(UrlResource::class, 'defaultUrl'),
        ];
    }
}
