<?php

namespace Dystcz\LunarApi\Tests\Feature\JsonApi\Extenstions;

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

    /**
     * Get all resource's attributes.
     */
    protected function allAttributes(): iterable
    {
        return $this->extendedFields($this->extension->attributes());
    }

    /**
     * Get all resource's relationships.
     */
    protected function allRelationships(): iterable
    {
        return $this->extendedFields($this->extension->relationships());
    }
}
