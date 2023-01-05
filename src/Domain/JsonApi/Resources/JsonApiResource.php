<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Resources;

use ArrayAccess;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use LaravelJsonApi\Contracts\Resources\Creatable;
use LaravelJsonApi\Contracts\Resources\JsonApiRelation;
use LaravelJsonApi\Contracts\Resources\Serializer\Attribute as SerializableAttribute;
use LaravelJsonApi\Contracts\Resources\Serializer\Relation as SerializableRelation;
use LaravelJsonApi\Contracts\Schema\Schema;
use LaravelJsonApi\Core\Document\Link;
use LaravelJsonApi\Core\Document\Links;
use LaravelJsonApi\Core\Document\ResourceIdentifier;
use LaravelJsonApi\Core\Resources\Concerns\ConditionallyLoadsFields;
use LaravelJsonApi\Core\Resources\Concerns\DelegatesToResource;
use LaravelJsonApi\Core\Resources\Relation;
use LaravelJsonApi\Core\Resources\RelationIterator;
use LaravelJsonApi\Core\Responses\Internal\ResourceResponse;
use LaravelJsonApi\Core\Schema\IdParser;
use LogicException;

/**
 * NOTE: Only change here is in visibility for relation() method from protected to public.
 */
class JsonApiResource extends \LaravelJsonApi\Core\Resources\JsonApiResource
{
    /**
     * Create a new resource relation.
     *
     * @param string $fieldName
     * @param string|null $keyName
     * @return Relation
     */
    public function relation(string $fieldName, string $keyName = null): Relation
    {
        return parent::relation($fieldName, $keyName);
    }
}
