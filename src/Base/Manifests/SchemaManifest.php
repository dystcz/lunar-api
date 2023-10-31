<?php

namespace Dystcz\LunarApi\Base\Manifests;

use Dystcz\LunarApi\Base\Contracts\SchemaExtension as SchemaExtensionContract;
use Dystcz\LunarApi\Base\Contracts\SchemaManifest as SchemaManifestContract;
use Dystcz\LunarApi\Domain\JsonApi\Contracts\Schema as SchemaContract;
use Dystcz\LunarApi\Domain\JsonApi\Eloquent\Schema;
use Dystcz\LunarApi\Support\Config\Collections\DomainConfigCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

/**
 * @property array<SchemaExtensionContract> $extensions
 */
class SchemaManifest extends Manifest implements SchemaManifestContract
{
    /**
     * Collection of registered schemas.
     */
    protected Collection $schemas;

    /**
     * @var Dystcz\LunarApi\Domain\JsonApi\Extensions\Contracts\SchemaExtension[]
     */
    public array $extensions;

    /**
     * The schema manifest instance.
     */
    public function __construct()
    {
        $this->schemas = new Collection([]);

        $this->registerBaseSchemas();
    }

    /**
     * Register schemas.
     *
     * @param  Collection<string,class-string>  $schemas
     */
    public function register(Collection $schemas): void
    {
        foreach ($schemas as $schemaClass) {
            $this->registerSchema($schemaClass);
        }
    }

    /**
     * Register single schema.
     *
     * @param  class-string  $schemaClass
     */
    public function registerSchema(string $schemaClass): void
    {
        /** @var Schema $schemaClass */
        $this->validateClassIsSchema($schemaClass);

        $this->schemas->put($schemaClass::type(), $schemaClass);
    }

    /**
     * Get list of all registered models.
     */
    public function getRegisteredSchemas(): Collection
    {
        return $this->schemas;
    }

    /**
     * Get list of registered schema types.
     */
    public function getSchemaTypes(): Collection
    {
        return $this->schemas->keys();
    }

    /**
     * Get the registered schema for a base schema class.
     */
    public function getRegisteredSchema(string $schemaType): SchemaContract
    {
        return App::make($this->models->get($schemaType) ?? $schemaType);
    }

    /**
     * Removes schema from manifest.
     */
    public function removeSchema(string $schemaType): void
    {
        $this->schemas = $this->schemas->flip()->forget($schemaType);
    }

    /**
     * Get schema extension for a given schema class.
     *
     * @param  class-string<Schema>  $class
     *
     * @deprecated Use SchemaManifest::extend() instead.
     */
    public static function for(string $class): SchemaExtensionContract
    {
        return self::extend($class);
    }

    /**
     * Get schema extension for a given schema class.
     *
     * @param  class-string<Schema>  $class
     */
    public static function extend(string $class): SchemaExtensionContract
    {
        $self = App::make(SchemaManifestContract::class);

        $extension = $self->extensions[$class] ??= App::make(SchemaExtensionContract::class, ['class' => $class]);

        return $extension;
    }

    /**
     * Register base schemas from config.
     */
    private function registerBaseSchemas(): void
    {
        $schemas = DomainConfigCollection::make()
            ->getSchemas();

        $this->register($schemas);
    }

    /**
     * Validate class is a schema.
     *
     * @throws \InvalidArgumentException
     */
    private function validateClassIsSchema(string $class): void
    {
        if (! class_implements($class, SchemaContract::class)) {
            throw new \InvalidArgumentException(sprintf('Given [%s] is not a subclass of [%s].', $class, Schema::class));
        }
    }
}
