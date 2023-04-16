<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Illuminate\Http\Request;

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

        if ($model->relationLoaded('product')) {
            $model->setRelation('attributes', $model->product->attributes);
        }

        return parent::attributes($request);
    }
}
