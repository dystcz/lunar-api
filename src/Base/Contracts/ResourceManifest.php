<?php

namespace Dystcz\LunarApi\Base\Contracts;

use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;

interface ResourceManifest extends Manifest
{
    /**
     * Get schema extension for a given schema class.
     *
     * @param  class-string<JsonApiResource>  $class
     *
     * @deprecated Use ResourceManifest::extend() instead.
     */
    public static function for(string $class): ResourceExtension;

    /**
     * Get resource extension for a given resouce class.
     *
     * @param  class-string<JsonApiResource>  $class
     */
    public static function extend(string $class): ResourceExtension;
}
