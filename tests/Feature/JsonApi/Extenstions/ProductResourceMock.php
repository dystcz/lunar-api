<?php

namespace Dystcz\LunarApi\Tests\Feature\JsonApi\Extenstions;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource\ResourceManifest;
use Dystcz\LunarApi\Domain\Products\JsonApi\V1\ProductResource;

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
