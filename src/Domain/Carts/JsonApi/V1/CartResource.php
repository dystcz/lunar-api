<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use Dystcz\LunarApi\Domain\Attributes\Collections\AttributeCollection;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\Products\Models\Product;

class CartResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  \Illuminate\Http\Request|null  $request
     * @return iterable
     */
    public function attributes($request): iterable
    {
        /** @var Cart $model */
        $model = $this->resource;

        if ($model->relationLoaded('lines')) {
            $model->calculate();
        }

        return [
            'subTotal' => $model->subTotal?->decimal,
            'shippingTotal' => $model->shippingTotal?->decimal,
            'taxTotal' => $model->taxTotal?->decimal,
            'cartDiscountAmount' => $model->cartDiscountAmount?->decimal,
            'discountTotal' => $model->discountTotal?->decimal,
            'total' => $model->total?->decimal,
            'taxBreakdown' => $model->taxBreakdown,

            ...ResourceManifest::for(static::class)->attributes()->toResourceArray($this),
        ];
    }

    /**
     * Get the resource's relationships.
     *
     * @param  \Illuminate\Http\Request|null  $request
     * @return iterable
     */
    public function relationships($request): iterable
    {
        /** @var Product */
        $model = $this->resource;

        return [
            $this->relation('lines'),

            ...ResourceManifest::for(static::class)->relationships()->toResourceArray($this),
        ];
    }
}
