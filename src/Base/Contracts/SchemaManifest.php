<?php

namespace Dystcz\LunarApi\Base\Contracts;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\Schema as SchemaContract;
use Illuminate\Support\Collection;

interface SchemaManifest extends Manifest
{
    /**
     * Register schemas.
     *
     * @param  Collection<string,class-string>  $schemas
     */
    public function register(Collection $schemas): void;

    /**
     * Register single schema.
     *
     * @param  class-string  $schemaClass
     */
    public function registerSchema(string $schemaClass): void;

    /**
     * Get the registered schema for a base schema class.
     */
    public function getRegisteredSchema(string $baseSchemaClass): SchemaContract;

    /**
     * Get list of registered schema types.
     */
    public function getSchemaTypes(): Collection;

    /**
     * Removes schema from manifest.
     */
    public function removeSchema(string $baseSchemaClass): void;

    /**
     * Get list of all registered models.
     */
    public function getRegisteredSchemas(): Collection;

    /**
     * Get schema extension for a given schema class.
     *
     * @param  class-string<SchemaContract>  $class
     *
     * @deprecated Use SchemaManifest::extend() instead.
     */
    public static function for(string $class): SchemaExtension;

    /**
     * Get schema extension for a given schema class.
     *
     * @param  class-string<SchemaContract>  $class
     */
    public static function extend(string $class): SchemaExtension;
}
