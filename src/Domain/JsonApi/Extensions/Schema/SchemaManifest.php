<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Manifest;
use Illuminate\Contracts\Container\BindingResolutionException;

class SchemaManifest implements Manifest
{
    protected array $extensions = [];

    /**
     * @param  class-string<Schema>  $class
     * @return SchemaExtension
     *
     * @throws BindingResolutionException
     */
    public static function for(string $class): Extension
    {
        $self = app()->make(self::class);

        return $self->extensions[$class]
            ??= app()->make(SchemaExtension::class, ['schemaClass' => $class]);
    }
}
