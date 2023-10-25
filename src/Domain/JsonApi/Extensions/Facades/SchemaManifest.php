<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Facades;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\Schema as SchemaContract;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\SchemaManifest as SchemaManifestContract;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Extension;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void register(Collection $schemas)
 * @method static void registerSchema(string $schemaClass)
 * @method static Collection getRegisteredSchemas()
 * @method static Collection getSchemaTypes()
 * @method static SchemaContract getRegisteredSchema(string $schemaType)
 * @method static void removeSchema(string $schemaType)
 * @method static Extension for(string $class)
 *
 * @see \Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest
 */
class SchemaManifest extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return SchemaManifestContract::class;
    }
}
