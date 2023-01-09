<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource;

use Closure;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\ExtensionCollection;

/**
 * @method ExtensionCollection attributes(Closure $callback = null)
 * @method ExtensionCollection relationships(Closure $callback = null)
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
