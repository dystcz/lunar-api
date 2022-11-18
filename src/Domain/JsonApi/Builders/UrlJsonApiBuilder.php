<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders;

use Dystcz\LunarApi\Domain\Urls\OpenApi\Schemas\UrlSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Lunar\Models\Url;

class UrlJsonApiBuilder extends JsonApiBuilder
{
    public static string $model = Url::class;

    public static string $schema = UrlSchema::class;

    public array $fields = [
        'id',
        'slug',
    ];

    public array $sorts = [
        'slug',
    ];

    public array $filters = [
        'slug',
    ];

    public array $includes = [];

    protected function attributesSchema(): array
    {
        return [
            Schema::string('slug')->example('mechanical-keyboards'),
        ];
    }
}
