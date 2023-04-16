<?php

namespace Dystcz\LunarApi\Tests\Feature\JsonApi\Extenstions;

use Closure;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\JsonApi\V1\Server;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Core\Resources\Relation;

uses(TestCase::class, RefreshDatabase::class);

test('a resource attributes can be extended', function () {
    ResourceManifest::for(ProductResourceMock::class)
        ->attributes(fn (JsonApiResource $resource) => [
            'secret_id' => $resource->resource->id,
            'nazdar' => 'cau',
            'ahoj' => 'zdar',
        ]);

    expect(ResourceManifest::for(ProductResourceMock::class)->attributes()[0])
        ->toBeInstanceOf(Closure::class);

    $server = App::make(Server::class, ['name' => 'v1']);

    $productSchemaInstance = $server->schemas()->schemaFor('products');

    $productResourceInstance = new ProductResourceMock(
        $productSchemaInstance,
        $product = ProductFactory::new()->create()
    );

    expect(iterator_to_array($productResourceInstance->attributes(null)))
        ->toBe([
            'secret_id' => $product->id,
            'nazdar' => 'cau',
            'ahoj' => 'zdar',
        ]);
});

test('a resource relationships can be extended', function () {
    ResourceManifest::for(ProductResourceMock::class)
        ->relationships(fn (JsonApiResource $resource) => [
            $resource->relation('golden_chocolate'),
        ]);

    expect(ResourceManifest::for(ProductResourceMock::class)->relationships()[0])
        ->toBeInstanceOf(Closure::class);

    $server = App::make(Server::class, ['name' => 'v1']);

    $productSchemaInstance = $server->schemas()->schemaFor('products');
    $productResourceInstance = new ProductResourceMock(
        $productSchemaInstance,
        ProductFactory::new()->create()
    );

    expect(iterator_to_array($productResourceInstance->relationships(null))['golden_chocolate'])
        ->toBeInstanceOf(Relation::class);
});
