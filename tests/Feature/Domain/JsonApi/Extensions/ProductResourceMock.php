<?php

namespace Dystcz\LunarApi\Tests\Feature\Domain\JsonApi\Extensions;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;

class ProductResourceMock extends JsonApiResource
{
    public function attributes($request): iterable
    {
        return parent::attributes($request);
    }

    public function relationships($request): iterable
    {
        return parent::relationships($request);
    }
}
