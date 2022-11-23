<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Resources;

use Dystcz\LunarApi\Domain\Attributes\Collections\AttributeCollection;
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

        return array_merge(
            AttributeCollection::make($model->mappedAttributes())
                ->mapToAttributeGroups($model)
                ->toArray(),
            [
                'variants_count' => $model->variants_count,
            ]
        );
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
