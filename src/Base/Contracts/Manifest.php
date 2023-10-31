<?php

namespace Dystcz\LunarApi\Base\Contracts;

interface Manifest
{
    /**
     * Get extension for given class.
     *
     * @param  class-string  $class
     *
     * @deprecated Use Manifest::extend() instead.
     */
    public static function for(string $class): Extension;

    /**
     * Get extension for a given class.
     *
     * @param  class-string<Schema>  $class
     */
    public static function extend(string $class): Extension;
}
