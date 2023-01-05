<?php

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Dystcz\LunarApi\Domain\JsonApi\V1\Server;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductResource;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

test('a resource attributes can be extended', function () {
    ResourceManifest::for(ProductResource::class)
        ->attributes(fn (JsonApiResource $resource) => [
            'secret_id' => $resource->resource->id,
        ]);

    expect(
        ResourceManifest::for(ProductResource::class)->attributes()->first(),
    )->toBeInstanceOf(Closure::class);

    $server = app()->make(Server::class, ['name' => 'v1']);

    $productSchemaInstance = $server->schemas()->schemaFor('products');
    $productResourceInstance = new ProductResourceMock(
        $productSchemaInstance,
        $product = ProductFactory::new()->create()
    );

    expect($productResourceInstance->attributes(null))
        ->toBe(['secret_id' => $product->id]);
});

test('a resource relationships can be extended', function () {
    ResourceManifest::for(ProductResource::class)
        ->relationships(fn (JsonApiResource $resource) => [
            $resource->relation('golden_chocolate'),
        ]);

    expect(
        ResourceManifest::for(ProductResource::class)->relationships()->first(),
    )->toBeInstanceOf(Closure::class);

    $server = app()->make(Server::class, ['name' => 'v1']);

    $productSchemaInstance = $server->schemas()->schemaFor('products');
    $productResourceInstance = new ProductResourceMock(
        $productSchemaInstance,
        ProductFactory::new()->create()
    );

    expect($productResourceInstance->relationships(null)[0])
        ->toBeInstanceOf(LaravelJsonApi\Core\Resources\Relation::class);
});

class ProductResourceMock extends ProductResource
{
    public function attributes($request): iterable
    {
        return [
            ...ResourceManifest::for(ProductResource::class)
                ->attributes()->toResourceArray($this),
        ];
    }

    public function relationships($request): iterable
    {
        return [
            ...ResourceManifest::for(ProductResource::class)
                ->relationships()->toResourceArray($this),
        ];
    }
}
