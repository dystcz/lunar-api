<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\Extendable;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;

/**
 * @property  class-string<Extendable>  $class
 * @property SchemaExtensionStore $store
 *
 * @method iterable|void with(mixed $value)
 * @method iterable|void includePaths(mixed $value)
 * @method iterable|void fields(mixed $value)
 * @method iterable|void filters(mixed $value)
 * @method iterable|void sortables(mixed $value)
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
