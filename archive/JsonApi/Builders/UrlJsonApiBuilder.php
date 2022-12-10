<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders;

use Dystcz\LunarApi\Domain\Urls\Http\Resources\UrlResource;
use Dystcz\LunarApi\Domain\Urls\OpenApi\Schemas\UrlSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Lunar\Models\Url;

class UrlJsonApiBuilder extends JsonApiBuilder
{
    public static string $model = Url::class;

    public static string $schema = UrlSchema::class;

    public static string $resource = UrlResource::class;

    public function fields(): array
    {
        return [
            'id',
            'slug',
        ];
    }

    public function sorts(): array
    {
        return [
            'slug',
        ];
    }

    public function filters(): array
    {
        return [
            'slug',
        ];
    }

    public function includes(): array
    {
        return [];
    }

    protected function attributesSchema(): array
    {
        return [
            Schema::string('slug')->example('mechanical-keyboards'),
        ];
    }
}
