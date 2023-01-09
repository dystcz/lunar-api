<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent;

use Dystcz\LunarApi\Domain\JsonApi\Extensions\Schema\SchemaManifest;
use LaravelJsonApi\Eloquent\Schema as BaseSchema;

abstract class Schema extends BaseSchema
{
    /**
     * {@inheritDoc}
     */
    public function with(): array
    {
        return [
            ...SchemaManifest::for(static::class)->with()->all(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function includePaths(): iterable
    {
        return [
            ...SchemaManifest::for(static::class)->includePaths()->all(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            ...SchemaManifest::for(static::class)->fields()->all(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function filters(): array
    {
        return [
            ...SchemaManifest::for(static::class)->filters()->all(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function sortables(): iterable
    {
        return [
            ...SchemaManifest::for(static::class)->sortables()->all(),
        ];
    }
}
