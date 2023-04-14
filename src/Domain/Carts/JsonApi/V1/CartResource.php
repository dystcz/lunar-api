<?php

namespace Dystcz\LunarApi\Domain\Carts\JsonApi\V1;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Illuminate\Http\Request;

class CartResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  Request|null  $request
     */
    public function attributes($request): iterable
    {
        /** @var Cart $model */
        $model = $this->resource;

        if ($model->relationLoaded('lines')) {
            $model->calculate();
        }

        return [
            'prices' => [
                'sub_total' => $model->subTotal?->decimal,
                'sub_total_discounted' => $model->subTotalDiscounted?->decimal,
                'total' => $model->total?->decimal,
                'shipping_total' => $model->shippingTotal?->decimal,
                'tax_total' => $model->taxTotal?->decimal,
                'discount_total' => $model->discountTotal?->decimal,
                'tax_breakdown' => $model->taxBreakdown,
                'discount_breakdown' => $model->discountBreakdown,
            ],

            ...parent::attributes($request),
        ];
    }

    /**
     * Get the resource's relationships.
     *
     * @param  Request|null  $request
     */
    public function relationships($request): iterable
    {
        return [
            $this->relation('lines'),
            $this->relation('order'),
            $this->relation('shippingAddress'),
            $this->relation('billingAddress'),
            $this->relation('addresses'),

            ...parent::relationships($request),
        ];
    }
}
