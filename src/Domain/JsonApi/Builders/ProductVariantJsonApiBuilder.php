<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders;

use Dystcz\LunarApi\Domain\Products\Http\Resources\ProductVariantIndexResource;
use Dystcz\LunarApi\Domain\Products\OpenApi\Schemas\ProductVariantSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Lunar\Models\ProductVariant;

class ProductVariantJsonApiBuilder extends JsonApiBuilder
{
    public static string $model = ProductVariant::class;

    public static string $schema = ProductVariantSchema::class;

    public static string $resource = ProductVariantIndexResource::class;

    public array $fields = [
        'id',
        'attribute_data',
    ];

    public array $sorts = [];

    public array $filters = [];

    public array $includes = [
        'basePrices' => PriceJsonApiBuilder::class,
        'prices' => PriceJsonApiBuilder::class,
        'images' => MediaJsonApiBuilder::class,
    ];

    protected function attributesSchema(): array
    {
        return [
            Schema::string('sku')->example('MK-LEO-FC750PS-BLUE'),
            Schema::string('ean')->example('MK-LEO-FC750PS-BLUE'),
            Schema::string('name')->example('Cherry MX Blue'),
        ];
    }
}
