<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Resources;

use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\Media\Http\Resources\MediaResource;
use Dystcz\LunarApi\Domain\Urls\Http\Resources\UrlResource;
use Illuminate\Http\Request;
use Lunar\Models\Product;
use TiMacDonald\JsonApi\Link;

class ProductIndexResource extends JsonApiResource
{
    protected function toAttributes(Request $request): array
    {
        /** @var Product */
        $model = $this->resource;

        return $model->productType->mappedAttributes
            ->groupBy(
                fn ($attribute) => $attribute->attributeGroup->handle
            )
            ->map(
                fn ($group) => $group->mapWithKeys(
                    fn ($attribute) => [$attribute->handle => $model->attr($attribute->handle)]
                )
            )->toArray();
    }

    protected function toRelationships(Request $request): array
    {
        return [
            'variants' => $this->optionalCollection(ProductVariantIndexResource::class, 'variants'),
            'thumbnail' => $this->optionalResource(MediaResource::class, 'thumbnail'),
            'defaultUrl' => $this->optionalResource(UrlResource::class, 'defaultUrl'),
        ];
    }

    protected function toLinks(Request $request): array
    {
        return [
            Link::self(route('products.show', $this->defaultUrl->slug)),
        ];
    }

    protected function toMeta(Request $request): array
    {
        return [
            // 'resourceDeprecated' => true,
        ];
    }
}
