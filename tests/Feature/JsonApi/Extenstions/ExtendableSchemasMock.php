<?php

namespace Dystcz\LunarApi\Tests\Feature\JsonApi\Extenstions;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema;

class ExtendableSchemasMock extends ProductSchema
{
    public function fields(): array
    {
        return [
            'product-name',
            ...SchemaManifest::for(ProductSchema::class)->fields()->all(),
        ];
    }
}
