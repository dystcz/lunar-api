<?php

namespace Dystcz\LunarApi\Base\Contracts;

use Closure;
use Dystcz\LunarApi\Base\Data\ExtensionValueCollection;

/**
 * @property  class-string<ExtendableContract>  $class
 *
 * @method ExtensionValueCollection attributes()
 * @method self setAttributes(iterable|callable $value)
 * @method ExtensionValueCollection relationships()
 * @method iterable setRelationships(iterable|callable $value)
 */
interface ResourceExtension extends Extension
{
    /** {@inheritdoc} */
    public function set(string $property, iterable|Closure $extension): Extension;

    /** {@inheritdoc} */
    public function get(string $key): iterable;
}
