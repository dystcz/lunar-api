<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders;

use Dystcz\LunarApi\Domain\Products\Http\Resources\ProductResource;
use Dystcz\LunarApi\Domain\Products\OpenApi\Schemas\ProductSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Lunar\Models\Product;

class ProductJsonApiBuilder extends JsonApiBuilder
{
    public static string $model = Product::class;

    public static string $schema = ProductSchema::class;

    public static string $resource = ProductResource::class;

    public array $fields = [
        'id',
        'attribute_data',
    ];

    public array $sorts = [];

    public array $filters = [];

    public array $includes = [
        'variants' => ProductVariantJsonApiBuilder::class,
        'defaultUrl' => UrlJsonApiBuilder::class,
        'thumbnail' => MediaJsonApiBuilder::class,
        'images' => MediaJsonApiBuilder::class,
    ];

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
        ];
    }
}
