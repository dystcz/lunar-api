<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource;

use Closure;
use Dystcz\LunarApi\Domain\JsonApi\Contracts\Extendable;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;

/**
 * @property  class-string<Extendable>  $class
 * @property ResourceExtensionStore $store
 *
 * @method void|iterable attributes(iterable|null $callback)
 * @method void|iterable relationships(Closure|null $callback)
 */
class ResourceExtension extends Extension
{
    /**
     * @param  class-string<Extendable>  $class
     */
    public function __construct(string $class)
    {
        $this->store = new ResourceExtensionStore();

        parent::__construct($class);
    }
}
