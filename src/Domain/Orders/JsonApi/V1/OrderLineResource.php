<?php

namespace Dystcz\LunarApi\Domain\Orders\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\Orders\Models\OrderLine;
use Illuminate\Http\Request;

class OrderLineResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  Request|null  $request
     */
    public function attributes($request): iterable
    {
        /** @var OrderLine $model */
        $model = $this->resource;

        return [
            'type' => $model->type,
            'description' => $model->description,
            'option' => $model->option,
            'identifier' => $model->identifier,
            'notes' => $model->notes,

            'unit_quantity' => $model->unit_quantity,
            'quantity' => $model->quantity,

            'prices' => [
                'unit_price' => $model->unit_price?->decimal,
                'sub_total' => $model->sub_total?->decimal,
                'tax_total' => $model->tax_total?->decimal,
                'total' => $model->total?->decimal,
                'discount_total' => $model->discount_total?->decimal,
                'tax_breakdown' => $model->taxBreakdown,
            ],
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
            $this->relation('purchasable'),
            $this->relation('order'),
            $this->relation('currency'),
        ];
    }
}
