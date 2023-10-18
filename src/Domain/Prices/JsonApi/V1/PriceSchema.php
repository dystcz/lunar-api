<?php

namespace Dystcz\LunarApi\Domain\Prices\JsonApi\V1;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Domain\Prices\Actions\GetPrice;
use Dystcz\LunarApi\Domain\Prices\Actions\GetPriceWithoutDefaultTax;
use Dystcz\LunarApi\Domain\Prices\JsonApi\Filters\MaxPriceFilter;
use Dystcz\LunarApi\Domain\Prices\JsonApi\Filters\MinPriceFilter;
use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Map;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\HashIds\HashId;
use Lunar\Models\Price;

class PriceSchema extends Schema
{
    /**
     * {@inheritDoc}
     */
    public static string $model = Price::class;

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            Config::get('lunar-api.schemas.use_hashids', false)
                ? HashId::make()
                : ID::make(),

            Map::make('base_price', [
                Str::make('formatted')
                    ->extractUsing(static function (Price $model) {
                        /** @var PriceDataType $basePrice */
                        $price = (new GetPrice)($model->price, $model->priceable);

                        return $price->formatted();
                    }),
                Number::make('decimal')
                    ->extractUsing(static function (Price $model) {
                        /** @var PriceDataType $basePrice */
                        $price = (new GetPrice)($model->price, $model->priceable);

                        return $price->decimal;
                    }),
                Number::make('value')
                    ->extractUsing(static function (Price $model) {
                        /** @var PriceDataType $basePrice */
                        $price = (new GetPrice)($model->price, $model->priceable);

                        return $price->value;
                    }),
            ]),

            Map::make('sub_price', [
                Str::make('formatted')
                    ->extractUsing(static function (Price $model) {
                        /** @var PriceDataType $basePrice */
                        $price = (new GetPriceWithoutDefaultTax)($model->price, $model->priceable);

                        return $price->formatted();
                    }),
                Number::make('decimal')
                    ->extractUsing(static function (Price $model) {
                        /** @var PriceDataType $basePrice */
                        $price = (new GetPriceWithoutDefaultTax)($model->price, $model->priceable);

                        return $price->decimal;
                    }),
                Number::make('value')
                    ->extractUsing(static function (Price $model) {
                        /** @var PriceDataType $basePrice */
                        $price = (new GetPriceWithoutDefaultTax)($model->price, $model->priceable);

                        return $price->value;
                    }),
            ]),

            Map::make('compare_price', [
                Str::make('formatted')
                    ->extractUsing(static function (Price $model) {
                        /** @var PriceDataType $comparePrice */
                        $comparePrice = (new GetPrice)($model->compare_price, $model->priceable);

                        return $comparePrice->formatted();
                    }),
                Number::make('decimal')
                    ->extractUsing(static function (Price $model) {
                        /** @var PriceDataType $comparePrice */
                        $comparePrice = (new GetPrice)($model->compare_price, $model->priceable);

                        return $comparePrice->decimal;
                    }),
                Number::make('value')
                    ->extractUsing(static function (Price $model) {
                        /** @var PriceDataType $comparePrice */
                        $comparePrice = (new GetPrice)($model->compare_price, $model->priceable);

                        return $comparePrice->value;
                    }),
            ]),

            ...parent::fields(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),

            MinPriceFilter::make('min_price', 'price'),

            MaxPriceFilter::make('max_price', 'price'),

            ...parent::filters(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function authorizable(): bool
    {
        return false; // TODO: create policies
    }

    /**
     * {@inheritDoc}
     */
    public static function type(): string
    {
        return 'prices';
    }
}
