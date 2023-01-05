<?php

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest;
use Dystcz\LunarApi\Domain\JsonApi\V1\Server;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

test('a schema can be extended', function () {
    SchemaManifest::for(ProductSchema::class)
        ->fields([
            'my-product-name',
        ]);

    expect(
        SchemaManifest::for(ProductSchema::class)->fields()->contains('my-product-name'),
    )->toBeTrue();

    $server = app()->make(ServerMock::class, ['name' => 'v1']);

    $productSchemaInstance = $server->schemas()->schemaFor('products');

    expect($productSchemaInstance->fields())->toBe([
        'product-name',
        'my-product-name',
    ]);
});

class ProductSchemaMock extends ProductSchema
{
    public function fields(): array
    {
        return [
            'product-name',
            ...SchemaManifest::for(ProductSchema::class)->fields()->all(),
        ];
    }
}

class ServerMock extends Server
{
    public function allSchemas(): array
    {
        return [
            ProductSchemaMock::class,
        ];
    }
}
