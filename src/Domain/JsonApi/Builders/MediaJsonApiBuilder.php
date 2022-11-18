<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders;

use Dystcz\LunarApi\Domain\Media\OpenApi\Schemas\MediaSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaJsonApiBuilder extends JsonApiBuilder
{
    public static string $model = Media::class;

    public static string $schema = MediaSchema::class;

    public array $fields = [
        'id',
        'url',
    ];

    public array $sorts = [];

    public array $filters = [];

    public array $includes = [];

    protected function attributesSchema(): array
    {
        return [
            // TODO fill in example value
            Schema::string('url')->example('some-url'),
        ];
    }
}
