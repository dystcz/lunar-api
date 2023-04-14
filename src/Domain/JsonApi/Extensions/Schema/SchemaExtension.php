<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\Extendable;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;

/**
 * @property  class-string<Extendable>  $class
 * @property SchemaExtensionStore $store
 *
 * @method iterale|void with(mixed $value)
 * @method iterale|void includePaths(mixed $value)
 * @method iterale|void fields(mixed $value)
 * @method iterale|void filters(mixed $value)
 * @method iterale|void sortables(mixed $value)
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
