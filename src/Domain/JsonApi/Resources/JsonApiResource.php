<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Resources;

use LaravelJsonApi\Core\Resources\Relation;

/**
 * NOTE: Only change here is in visibility for relation() method from protected to public.
 */
class JsonApiResource extends \LaravelJsonApi\Core\Resources\JsonApiResource
{
    /**
     * Create a new resource relation.
     *
     * @param  string  $fieldName
     * @param  string|null  $keyName
     * @return Relation
     */
    public function relation(string $fieldName, string $keyName = null): Relation
    {
        return parent::relation($fieldName, $keyName);
    }
}
