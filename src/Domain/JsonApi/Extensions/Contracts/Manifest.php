<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts;

interface Manifest
{
    /**
     * Get extension for given class.
     *
     * @param  class-string  $class
     */
    public static function for(string $class): Extension;
}
