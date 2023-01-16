<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource;

use Closure;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\ExtensionCollection;

/**
 * @method ResourceExtensionCollection attributes(Closure $callback = null)
 * @method ResourceExtensionCollection relationships(Closure $callback = null)
 */
class ResourceExtension extends Extension
{
    protected ExtensionCollection $attributes;

    protected ExtensionCollection $relationships;

    public function __construct()
    {
        $this->attributes = new ResourceExtensionCollection();
        $this->relationships = new ResourceExtensionCollection();
    }
}
