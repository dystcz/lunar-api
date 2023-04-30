<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema;

use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\SchemaExtension as SchemaExtensionContract;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\SchemaManifest as SchemaManifestContract;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Manifest;
use Illuminate\Support\Facades\App;

/**
 * @property array<SchemaExtensionContract> $extensions
 */
class SchemaManifest extends Manifest implements SchemaManifestContract
{
    /**
     * Get schema extension for a given schema class.
     *
     * @param  class-string<Schema>  $class
     */
    public static function for(string $class): SchemaExtensionContract
    {
        $self = App::make(SchemaManifestContract::class);

        $extension = $self->extensions[$class] ??= App::make(SchemaExtensionContract::class, ['class' => $class]);

        return $extension;
    }
}
