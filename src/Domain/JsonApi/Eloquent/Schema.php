<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent;

use Dystcz\LunarApi\Domain\JsonApi\Contracts\Extendable;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaExtension;
use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest;
use LaravelJsonApi\Eloquent\Schema as BaseSchema;

abstract class Schema extends BaseSchema implements Extendable
{
    /**
     * Schema extension.
     */
    protected SchemaExtension $extension;

    /**
     * {@inheritDoc}
     */
    public function with(): array
    {
        return [];

        return [
            ...SchemaManifest::for(static::class)->with()->all(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [];

        return [
            ...SchemaManifest::for(static::class)->includePaths()->all(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [];

        return [
            ...SchemaManifest::for(static::class)->fields()->all(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function filters(): array
    {
        return [];

        return [
            ...SchemaManifest::for(static::class)->filters()->all(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function sortables(): iterable
    {
        return [];

        return [
            ...SchemaManifest::for(static::class)->sortables()->all(),
        ];
    }
}
