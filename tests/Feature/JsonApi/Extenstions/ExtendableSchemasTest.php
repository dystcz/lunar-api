<?php

namespace Dystcz\LunarApi\Tests\Feature\JsonApi\Extenstions;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductSchema;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;

uses(TestCase::class, RefreshDatabase::class);

test('a schema can be extended', function () {
    SchemaManifest::for(ProductSchema::class)
        ->fields([
            'my-product-name',
        ]);

    expect(
        SchemaManifest::for(ProductSchema::class)->fields()->contains('my-product-name'),
    )->toBeTrue();

    $server = App::make(ServerMock::class, ['name' => 'v1']);

    $productSchemaInstance = $server->schemas()->schemaFor('products');

    expect($productSchemaInstance->fields())->toBe([
        'product-name',
        'my-product-name',
    ]);
});
