<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Http\Resources;

use Dystcz\LunarApi\Domain\JsonApi\Builders\ProductVariantJsonApiBuilder;
use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Lunar\Models\ProductVariant;

class ProductVariantResource extends JsonApiResource
{
    protected function toAttributes(Request $request): array
    {
        /** @var ProductVariant $model */
        $model = $this->resource;

        return [
            'sku' => $this->sku,
            'ean' => $this->ean,
            ...! $this->attribute_data
                ? []
                : $model->attribute_data->keys()->mapWithKeys(
                    fn ($key) => [$key => $this->attr($key)]
                ),
            'images_count' => fn () => $this->images_count,
        ];
    }

    protected function toRelationships(Request $request): array
    {
        return App::get(ProductVariantJsonApiBuilder::class)->toRelationships($this->resource);
    }
}
