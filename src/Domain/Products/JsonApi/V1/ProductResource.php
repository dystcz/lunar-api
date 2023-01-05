<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\V1;

use Dystcz\LunarApi\Domain\Attributes\Collections\AttributeCollection;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Lunar\Models\Product;

class ProductResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param \Illuminate\Http\Request|null $request
     * @return iterable
     */
    public function attributes($request): iterable
    {
        /** @var Product */
        $model = $this->resource;

        if ($model->relationLoaded('variants')) {
            $model->variants->each(fn ($variant) => $variant->setRelation('product', $model));
        }

        // dd($model->productType->mappedAttributes);
        // dd($model->productType->getMorphClass());

        // dd($model->productType->mappedAttributes, $model->productType->productAttributes);

        return [
            'product_type' => $this->productType->name,
            $this->mergeWhen(
                $model->relationLoaded('productType'),
                fn () => AttributeCollection::make($model->productType->productAttributes)
                    ->mapToAttributeGroups($model)
                    ->toArray()
            ),
            ...ResourceManifest::for(static::class)->attributes()->toResourceArray($this),
        ];
    }

    /**
     * Get the resource's relationships.
     *
     * @param \Illuminate\Http\Request|null $request
     * @return iterable
     */
    public function relationships($request): iterable
    {
        /** @var Product */
        $model = $this->resource;

        return [
            $this->relation('associations'),

            $this->relation('brand'),

            $this->relation('cheapest_variant'),

            $this
                ->relation('collections')
                ->withoutLinks()
                ->withMeta(
                    array_filter([
                        'count' => $model->collections_count,
                    ], fn ($value) => null !== $value)
                ),

            $this->relation('default_url'),

            $this
                ->relation('images')
                ->withoutLinks()
                ->withMeta(
                    array_filter([
                        'count' => $model->images_count,
                    ], fn ($value) => null !== $value)
                ),

            $this->relation('lowest_price'),

            $this->relation('prices'),

            $this
                ->relation('thumbnail')
                ->withoutLinks(),

            $this
                ->relation('urls')
                ->withoutLinks(),

            $this
                ->relation('variants')
                ->withMeta(
                    array_filter([
                        'count' => $model->variants_count,
                    ], fn ($value) => null !== $value)
                ),

            ...ResourceManifest::for(static::class)->relationships()->toResourceArray($this),
        ];
    }
}
