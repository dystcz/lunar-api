<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders;

use Dystcz\LunarApi\Domain\JsonApi\Builders\CustomIncludes\AggregateInclude;
use Dystcz\LunarApi\Domain\JsonApi\Builders\Elements\IncludeElement;
use Dystcz\LunarApi\Domain\Products\Http\Resources\ProductResource;
use Dystcz\LunarApi\Domain\Products\OpenApi\Schemas\ProductSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Lunar\Models\Product;
use Spatie\QueryBuilder\AllowedInclude;

class ProductJsonApiBuilder extends JsonApiBuilder
{
    public static string $model = Product::class;

    public static string $schema = ProductSchema::class;

    public static string $resource = ProductResource::class;

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
            IncludeElement::make('variants', ProductVariantJsonApiBuilder::class)
                ->withCount(),
            IncludeElement::make('defaultUrl', UrlJsonApiBuilder::class),
            IncludeElement::make('thumbnail', MediaJsonApiBuilder::class),
            IncludeElement::make('images', MediaJsonApiBuilder::class)
                ->withCount(),

            // Custom includes.
            // AllowedInclude::custom('sumOfIds', new AggregateInclude('id', 'sum'))
        ];
    }

    protected function attributesSchema(): array
    {
        return [
            Schema::string('name')->example('Leopold FC980PD (Black)'),
            Schema::string('description')->example('<div>Leopold FC980PD (Black)</div>'),
            Schema::string('teaser')->example('<p>Leopold FC980PD (Black)</p>'),
            Schema::boolean('led')->example(true),
            Schema::string('colors')->example('black'),
            Schema::array('switch_manufacturer')->example(['Cherry']),
            Schema::integer('keyboard_size')->example(90),
            Schema::string('layout')->example('ansi'),
            Schema::string('seo_title')->example('Leopold FC980PD (Black)'),
            Schema::string('seo_description')->example('<div>Leopold FC980PD (Black)</div>'),
            Schema::number('variants_count')->example(5),
            Schema::number('images_count')->example(5),
        ];
    }
}
