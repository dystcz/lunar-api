<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\JsonApi\V1;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use Lunar\Models\ProductVariant;

class ProductVariantResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param \Illuminate\Http\Request|null $request
     * @return iterable
     */
    public function attributes($request): iterable
    {
        /** @var ProductVariant */
        $model = $this->resource;

        return [
            'sku' => $model->sku,
            'stock' => $model->stock,
            'purchasable' => $model->purchasable,
            //todo: add mappedAttributes
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
        return [
            $this->relation('images'),
            $this->relation('prices'),
        ];
    }
}
