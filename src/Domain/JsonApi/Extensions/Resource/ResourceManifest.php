<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Manifest;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Illuminate\Support\Facades\App;

class ResourceManifest implements Manifest
{
    /**
     * @var array<string, ResourceExtension>
     */
    protected array $extensions = [];

    /**
     * @param  class-string<JsonApiResource>  $class
     * @return ResourceExtension
     */
    public static function for(string $class): Extension
    {
        $self = App::make(self::class);

        return $self->extensions[$class] ??= App::make(ResourceExtension::class, ['resourceClass' => $class]);
    }
}
