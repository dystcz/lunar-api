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

    public array $fields = [
        'id',
        'name',
        'code',
    ];

    public array $sorts = [
        'name',
        'code',
    ];

    public array $filters = [
        'name',
        'code',
    ];

    public array $includes = [];

    protected function attributesSchema(): array
    {
        return [
            Schema::integer('name')->example('EURO'),
            Schema::integer('code')->example('EUR'),
        ];
    }
}
