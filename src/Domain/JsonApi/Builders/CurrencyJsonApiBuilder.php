<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders;

use Dystcz\LunarApi\Domain\Prices\Http\Resources\CurrencyResource;
use Dystcz\LunarApi\Domain\Prices\OpenApi\Schemas\CurrencySchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Lunar\Models\Currency;

class CurrencyJsonApiBuilder extends JsonApiBuilder
{
    public static string $model = Currency::class;

    public static string $schema = CurrencySchema::class;

    public static string $resource = CurrencyResource::class;

    public function fields(): array
    {
        return [
            'id',
            'name',
            'code', ];
    }

    public function sorts(): array
    {
        return [
            'name',
            'code', ];
    }

    public function filters(): array
    {
        return [
            'name',
            'code', ];
    }

    public function includes(): array
    {
        return [];
    }

    protected function attributesSchema(): array
    {
        return [
            Schema::integer('name')->example('EURO'),
            Schema::integer('code')->example('EUR'),
        ];
    }
}
