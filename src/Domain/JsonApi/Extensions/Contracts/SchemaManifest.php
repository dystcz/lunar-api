<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\Schema as SchemaContract;
use Illuminate\Support\Collection;

interface SchemaManifest extends Manifest
{
    /**
     * Register schemas.
     *
     * @param  Collection<class-string,class-string>  $schemas
     */
    public function register(Collection $schemas): void;

    /**
     * Get the registered schema for a base schema class.
     */
    public function getRegisteredSchema(string $baseSchemaClass): SchemaContract;

    /**
     * Removes schema from manifest.
     */
    public function removeSchema(string $baseSchemaClass): void;

    /**
     * Get list of registered schema types.
     */
    public function getSchemaTypes(): Collection;

    /**
     * Get list of all registered models.
     */
    public function getRegisteredSchemas(): Collection;

    /** {@inheritDoc} */
    public static function for(string $class): Extension;
}
