<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Builders;

use Dystcz\LunarApi\Domain\JsonApi\Builders\Elements\IncludeElement;
use Dystcz\LunarApi\Domain\Products\Http\Resources\ProductVariantResource;
use Dystcz\LunarApi\Domain\Products\OpenApi\Schemas\ProductVariantSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Lunar\Models\ProductVariant;

class ProductVariantJsonApiBuilder extends JsonApiBuilder
{
    public static string $model = ProductVariant::class;

    public static string $schema = ProductVariantSchema::class;

    public static string $resource = ProductVariantResource::class;

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
            IncludeElement::make('basePrices', PriceJsonApiBuilder::class),
            IncludeElement::make('prices', PriceJsonApiBuilder::class),
            IncludeElement::make('images', MediaJsonApiBuilder::class)
                ->withCount(),
        ];
    }

    protected function attributesSchema(): array
    {
        return [
            Schema::string('sku')->example('MK-LEO-FC750PS-BLUE'),
            Schema::string('ean')->example('MK-LEO-FC750PS-BLUE'),
            Schema::string('name')->example('Cherry MX Blue'),
            Schema::number('images_count')->example(5),
        ];
    }
}
