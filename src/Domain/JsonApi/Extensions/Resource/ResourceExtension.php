<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\Extendable as ExtendableContract;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\ResourceExtension as ResourceExtensionContract;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Data\ExtensionValueCollection;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;

/**
 * @property  class-string<ExtendableContract>  $class
 *
 * @method ExtensionValueCollection attributes()
 * @method self setAttributes(iterable|callable $value)
 * @method ExtensionValueCollection relationships()
 * @method iterable setRelationships(iterable|callable $value)
 */
class ResourceExtension extends Extension implements ResourceExtensionContract
{
    public function __construct(
        protected string $class,

        /**
         * Attributes method extensions.
         */
        protected ExtensionValueCollection $attributes = new ExtensionValueCollection,

        /**
         * Relationships method extensions.
         */
        protected ExtensionValueCollection $relationships = new ExtensionValueCollection,
    ) {
        parent::__construct($class);
    }
}
