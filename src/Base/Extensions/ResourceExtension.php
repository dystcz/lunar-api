<?php

namespace Dystcz\LunarApi\Base\Extensions;

use Dystcz\LunarApi\Base\Contracts\Extendable as ExtendableContract;
use Dystcz\LunarApi\Base\Contracts\ResourceExtension as ResourceExtensionContract;
use Dystcz\LunarApi\Base\Data\ExtensionValueCollection;

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
