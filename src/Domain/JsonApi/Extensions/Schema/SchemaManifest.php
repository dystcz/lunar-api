<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\Manifest as ManifestContract;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Manifest;
use Illuminate\Support\Facades\App;

/**
 * @property array $extensions
 */
class SchemaManifest extends Manifest implements ManifestContract
{
    /**
     * Get schema extension for a given schema class.
     *
     * @param  class-string<Schema>  $class
     * @return SchemaExtension
     */
    public static function for(string $class): Extension
    {
        $self = App::make(self::class);

        $extension = $self->extensions[$class] ??= App::make(SchemaExtension::class, ['class' => $class]);

        return $extension;
    }
}
