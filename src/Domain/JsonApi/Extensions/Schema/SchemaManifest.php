<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Manifest;
use Illuminate\Support\Facades\App;

class SchemaManifest implements Manifest
{
    protected array $extensions = [];

    /**
     * @param  class-string<Schema>  $class
     * @return SchemaExtension
     */
    public static function for(string $class): Extension
    {
        $self = App::make(self::class);

        return $self->extensions[$class]
            ??= App::make(SchemaExtension::class, ['schemaClass' => $class]);
    }
}
