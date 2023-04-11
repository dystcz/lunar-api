<?php

namespace Dystcz\LunarApi\Domain\Prices\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\Prices\Actions\GetPrice;
use Dystcz\LunarApi\Domain\Prices\Models\Price as PriceModel;
use Illuminate\Http\Request;

class PriceResource extends JsonApiResource
{
    private GetPrice $getPrice;

    public function __construct()
    {
        $this->getPrice = new GetPrice;
    }

    /**
     * Get the resource's attributes.
     *
     * @param  Request|null  $request
     */
    public function attributes($request): iterable
    {
        /** @var PriceModel $model */
        $model = $this->resource;

        /** @var PriceDataType $basePrice */
        $price = ($this->getPrice)($model->price);

        /** @var PriceDataType $comparePrice */
        $comparePrice = ($this->getPrice)($model->compare_price);

        return [
            'base_price' => [
                'formatted' => $price->formatted(),
                'decimal' => $price->decimal,
                'value' => $price->value,
            ],
            'compare_price' => [
                'formatted' => $comparePrice->formatted(),
                'decimal' => $comparePrice->decimal,
                'value' => $comparePrice->value,
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
        return [
            ...ResourceManifest::for(static::class)->relationships()->toResourceArray($this),
        ];
    }
}
