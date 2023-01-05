<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Resource;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Manifest;
use Dystcz\LunarApi\Domain\JsonApi\Resources\JsonApiResource;
use Illuminate\Contracts\Container\BindingResolutionException;

class ResourceManifest implements Manifest
{
    /**
     * @var array<string, ResourceExtension>
     */
    protected array $extensions = [];

    /**
     * @param  class-string<JsonApiResource>  $class
     * @return ResourceExtension
     *
     * @throws BindingResolutionException
     */
    public static function for(string $class): Extension
    {
        $self = app()->make(self::class);

        return $self->extensions[$class]
            ??= app()->make(ResourceExtension::class, ['resourceClass' => $class]);
    }
}
