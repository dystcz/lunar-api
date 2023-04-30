<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts;

use Closure;

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
