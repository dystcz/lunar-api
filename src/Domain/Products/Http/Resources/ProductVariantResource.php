<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Resources;

use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\Media\Http\Resources\MediaResource;
use Dystcz\LunarApi\Domain\Prices\Http\Resources\PriceResource;
use Illuminate\Http\Request;
use Lunar\Models\ProductVariant;

class ProductVariantResource extends JsonApiResource
{
    protected function toAttributes(Request $request): array
    {
        /** @var ProductVariant */
        $model = $this->resource;

        return [
            'sku' => $this->sku,
            'ean' => $this->ean,
            ...!$this->attribute_data
                ? []
                : $model->attribute_data->keys()->mapWithKeys(
                    fn ($key) => [$key => $this->attr($key)]
                ),
        ];
    }

    protected function toRelationships(Request $request): array
    {
        return [
            'basePrices' => $this->optionalCollection(PriceResource::class, 'basePrices'),
            'prices' => $this->optionalCollection(PriceResource::class, 'prices'),
            'images' => $this->optionalCollection(MediaResource::class, 'images'),
        ];
    }
}
