<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders;

use Dystcz\LunarApi\Domain\JsonApi\Builders\Elements\IncludeElement;
use Dystcz\LunarApi\Domain\Prices\Http\Resources\PriceResource;
use Dystcz\LunarApi\Domain\Prices\OpenApi\Schemas\PriceSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Lunar\Models\Price;

class PriceJsonApiBuilder extends JsonApiBuilder
{
    public static string $model = Price::class;

    public static string $schema = PriceSchema::class;

    public static string $resource = PriceResource::class;

    public function fields(): array
    {
        return [
            'id',
            'price', ];
    }

    public function sorts(): array
    {
        return [
            'price',
        ];
    }

    public function filters(): array
    {
        return [
            'price',
        ];
    }

    public function includes(): array
    {
        return [
            IncludeElement::make('currency', CurrencyJsonApiBuilder::class),
        ];
    }

    protected function attributesSchema(): array
    {
        return [
            Schema::number('price')->example(9.99),
        ];
    }
}
