<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1;

use Dystcz\LunarApi\Domain\Attributes\Collections\AttributeCollection;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Lunar\Models\ProductVariant;

class ProductVariantResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  \Illuminate\Http\Request|null  $request
     */
    public function attributes($request): iterable
    {
        /** @var ProductVariant */
        $model = $this->resource;

        if ($model->relationLoaded('prices')) {
            $model->prices->each(fn ($price) => $price->setRelation('purchasable', $model));
        }

        if ($model->relationLoaded('product')) {
            $model->product->each(fn ($variant) => $variant->setRelation('variants', $model));
        }

        return [
            'sku' => $model->sku,
            'stock' => $model->stock,
            'purchasable' => $model->purchasable,
            $this->mergeWhen(
                $model->relationLoaded('product'),
                fn () => AttributeCollection::make($model->product->productType->variantAttributes)
                    ->mapToAttributeGroups($model)
                    ->toArray()
            ),
            ...ResourceManifest::for(static::class)->attributes()->toResourceArray($this),
        ];
    }

    /**
     * Get the resource's relationships.
     *
     * @param  \Illuminate\Http\Request|null  $request
     */
    public function relationships($request): iterable
    {
        return [
            $this->relation('product'),
            $this->relation('lowestPrice'),
            $this->relation('images'),
            $this->relation('prices'),
            ...ResourceManifest::for(static::class)->relationships()->toResourceArray($this),
        ];
    }
}
