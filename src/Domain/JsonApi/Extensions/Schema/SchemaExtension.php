<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\Extendable;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;

/**
 * @property  class-string<Extendable>  $class
 * @property SchemaExtensionStore $store
 *
 * @method array|self with(mixed $value)
 * @method array|self includePaths(mixed $value)
 * @method array|self fields(mixed $value)
 * @method array|self filters(mixed $value)
 * @method array|self sortables(mixed $value)
 * @method array|self showRelated(mixed $value)
 * @method array|self showRelationships(mixed $value)
 */
class SchemaExtension extends Extension
{
    /**
     * @param  class-string<Extendable>  $class
     */
    public function __construct(string $class)
    {
        $this->store = new SchemaExtensionStore();

        parent::__construct($class);
    }
}
