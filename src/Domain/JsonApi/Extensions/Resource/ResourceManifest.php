<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\Manifest as ManifestContract;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Manifest;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Illuminate\Support\Facades\App;

/**
 * @property array $extensions
 */
class ResourceManifest extends Manifest implements ManifestContract
{
    /**
     * Get resource extension for a given schema class.
     *
     * @param  class-string<JsonApiResource>  $class
     * @return ResourceExtension
     */
    public static function for(string $class): Extension
    {
        $self = App::make(self::class);

        $extension = $self->extensions[$class] ??= App::make(ResourceExtension::class, ['class' => $class]);

        return $extension;
    }
}
