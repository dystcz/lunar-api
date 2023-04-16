<?php

namespace Dystcz\LunarApi\Domain\Products\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Illuminate\Http\Request;

class ProductResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  Request|null  $request
     */
    public function attributes($request): iterable
    {
        /** @var Product $model */
        $model = $this->resource;

        if ($model->relationLoaded('variants')) {
            $model->variants->each(fn ($variant) => $variant->setRelation('product', $model));
        }

        return parent::attributes($request);
    }
}
