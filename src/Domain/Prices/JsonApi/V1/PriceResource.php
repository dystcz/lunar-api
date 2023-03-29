<?php

namespace Dystcz\LunarApi\Domain\Prices\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\Prices\Actions\GetPriceWithDefaultTax;
use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Illuminate\Support\Facades\Config;

class PriceResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  \Illuminate\Http\Request|null  $request
     */
    public function attributes($request): iterable
    {
        /** @var bool $showPricesWithTax */
        $showPricesWithTax = Config::get('lunar-api.taxation.prices_with_default_tax');

        /** @var Price $model */
        $model = $this->resource;

        /** @var PriceDataType $basePrice */
        $basePrice = $model->price;

        if ($showPricesWithTax) {
            $basePrice = (new GetPriceWithDefaultTax())($model->priceable, $basePrice);
        }

        /** @var PriceDataType|null $comparePrice */
        $comparePrice = $model->compare_price->value > $model->price->value ? $model->compare_price : null;

        if ($showPricesWithTax && $comparePrice) {
            $comparePrice = (new GetPriceWithDefaultTax())($model->priceable, $comparePrice);
        }

        return [
            'base_price' => [
                'formatted' => $basePrice->formatted(),
                'decimal' => $basePrice->decimal,
                'value' => $basePrice->value,
            ],
            'compare_price' => [
                'formatted' => $comparePrice?->formatted(),
                'decimal' => $comparePrice?->decimal,
                'value' => $comparePrice?->value,
            ],
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
            ...ResourceManifest::for(static::class)->relationships()->toResourceArray($this),
        ];
    }
}
