<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders;

use Dystcz\LunarApi\Domain\Collections\Http\Resources\CollectionResource;
use Dystcz\LunarApi\Domain\Collections\OpenApi\Schemas\CollectionSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Lunar\Models\Collection;

class CollectionJsonApiBuilder extends JsonApiBuilder
{
    public static string $model = Collection::class;

    public static string $schema = CollectionSchema::class;

    public static string $resource = CollectionResource::class;

    public array $fields = [
        'id',
        'attribute_data',
    ];

    public array $sorts = [];

    public array $filters = [];

    public array $includes = [
        'products' => ProductJsonApiBuilder::class,
        'defaultUrl' => UrlJsonApiBuilder::class,
        'thumbnail' => MediaJsonApiBuilder::class,
    ];

    protected function attributesSchema(): array
    {
        return [
            Schema::string('name')->example('Mechanical keyboards'),
        ];
    }
}
