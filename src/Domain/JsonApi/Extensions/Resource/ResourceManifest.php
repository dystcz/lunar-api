<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\ResourceExtension as ResourceExtensionContract;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\ResourceManifest as ResourceManifestContract;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Manifest;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Illuminate\Support\Facades\App;

/**
 * @property array<ResourceExtensionContract> $extensions
 */
class ResourceManifest extends Manifest implements ResourceManifestContract
{
    /**
     * Get resource extension for a given schema class.
     *
     * @param  class-string<JsonApiResource>  $class
     */
    public static function for(string $class): ResourceExtensionContract
    {
        $self = App::make(ResourceManifestContract::class);

        $extension = $self->extensions[$class] ??= App::make(ResourceExtensionContract::class, ['class' => $class]);

        return $extension;
    }
}
