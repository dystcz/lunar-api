<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Resources;

use Dystcz\LunarApi\Domain\Attributes\Collections\AttributeCollection;
use Dystcz\LunarApi\Domain\JsonApi\Builders\ProductJsonApiBuilder;
use Dystcz\LunarApi\Domain\JsonApi\Http\Resources\JsonApiResource;
use Illuminate\Http\Request;
use Lunar\Models\Product;
use TiMacDonald\JsonApi\Link;

class ProductResource extends JsonApiResource
{
    protected function toAttributes(Request $request): array
    {
        /** @var Product */
        $model = $this->resource;

        return array_merge(
            AttributeCollection::make($model->mappedAttributes())
                ->mapToAttributeGroups($model)
                ->toArray(),
            ['variants_count' => fn () => $model->variants_count],
            ['images_count' => fn () => $model->images_count],
        );
    }

    protected function toRelationships(Request $request): array
    {
        return app(ProductJsonApiBuilder::class)->toRelationships($this->resource);
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
