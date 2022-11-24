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
        'file_name',
        'mime_type',
        'size',
        'collection_name',
        'order_column',
    ];

    public array $sorts = [
        'order_column',
    ];

    public array $filters = [
        'collection_name',
    ];

    public array $includes = [];

    protected function attributesSchema(): array
    {
        return [
            // TODO fill in example value
            Schema::string('path')->example('1/image.jpg'),
            Schema::string('url')->example('https://example.com/storage/1/image.jpg'),
            Schema::string('file_name')->example('example.jpg'),
            Schema::string('mime_type')->example('image/jpeg'),
            Schema::number('size')->example(69),
            Schema::string('collection_name')->example('images'),
            Schema::number('order_column')->example(1),
        ];
    }
}
