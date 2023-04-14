<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Contracts;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;

interface Manifest
{
    /**
     * Get extension for given class.
     *
     * @param  class-string  $class
     */
    public static function for(string $class): Extension;
}
