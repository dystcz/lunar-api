<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts;

use Closure;

interface Extension
{
    /**
     * Set extension value.
     *
     * Closure will be called with Extendable
     * instance as argument and Closure will be bound to its scope,
     * so you can use $this to refference the Extendable instance.
     *
     * @param  iterable|Closure(ExtendableContract):((array))  $value
     */
    public function set(string $property, iterable|Closure $extension): Extension;

    /**
     * Get property extension values.
     */
    public function get(string $key): iterable;
}
