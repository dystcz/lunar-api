<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1;

use Dystcz\LunarApi\Domain\Attributes\Collections\AttributeCollection;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Illuminate\Http\Request;
use Lunar\Models\ProductVariant;

class ProductVariantResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  Request|null  $request
     */
    public function attributes($request): iterable
    {
        /** @var ProductVariant */
        $model = $this->resource;

        if ($model->relationLoaded('prices')) {
            $model->prices->each(fn ($price) => $price->setRelation('purchasable', $model));
        }

        return [
            $this->mergeWhen(
                $model->relationLoaded('product'),
                fn () => AttributeCollection::make($model->product->productType->variantAttributes)
                    ->mapToAttributeGroups($model)
                    ->toArray()
            ),

            'purchasability' => [
                'purchase_status' => $model->purchaseStatus->toArray(),
            ],

            ...ResourceManifest::for(static::class)->attributes()->toResourceArray($this),
        ];
    }

    /**
     * Get the resource's relationships.
     *
     * @param  Request|null  $request
     */
    public function relationships($request): iterable
    {
        /** @var ProductVariant $model */
        $model = $this->resource;

        return [
            $this->relation('product'),

            $this
                ->relation('images')
                ->withoutLinks()
                ->withMeta(
                    array_filter([
                        'count' => $model->images_count,
                    ], fn ($value) => null !== $value)
                ),

            $this->relation('lowest_price')
                ->withoutLinks(),

            $this->relation('prices')
                ->withoutLinks(),

            ...ResourceManifest::for(static::class)->relationships()->toResourceArray($this),
        ];
    }
}
