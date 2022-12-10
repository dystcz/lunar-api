<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders;

use Dystcz\LunarApi\Domain\Collections\Http\Resources\CollectionResource;
use Dystcz\LunarApi\Domain\Collections\OpenApi\Schemas\CollectionSchema;
use Dystcz\LunarApi\Domain\JsonApi\Builders\Elements\IncludeElement;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Lunar\Models\Collection;

class CollectionJsonApiBuilder extends JsonApiBuilder
{
    public static string $model = Collection::class;

    public static string $schema = CollectionSchema::class;

    public static string $resource = CollectionResource::class;

    public function fields(): array
    {
        return [
            'id',
            'attribute_data',
        ];
    }

    public function sorts(): array
    {
        return [];
    }

    public function filters(): array
    {
        return [];
    }

    public function includes(): array
    {
        return [
            IncludeElement::make('products', ProductJsonApiBuilder::class)
                ->withCount(),
            IncludeElement::make('defaultUrl', UrlJsonApiBuilder::class),
            IncludeElement::make('thumbnail', MediaJsonApiBuilder::class),
        ];
    }

    protected function attributesSchema(): array
    {
        return [
            Schema::string('name')->example('Mechanical keyboards'),
            Schema::number('products_count')->example(5),
        ];
    }
}
