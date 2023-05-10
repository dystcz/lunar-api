<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent;

use LaravelJsonApi\Eloquent\Contracts\Driver;
use LaravelJsonApi\Eloquent\Contracts\Parser;
use LaravelJsonApi\Eloquent\Repository as BaseRepository;

class Repository extends BaseRepository
{
    /**
     * Repository constructor.
     */
    public function __construct(Schema $schema, Driver $driver, Parser $parser)
    {
        parent::__construct($schema, $driver, $parser);
    }

    /**
     * Get the model for the supplied resource id.
     */
    public function find(string $resourceId): ?object
    {
        return parent::find($resourceId);
    }

    /**
     * Get the models for the supplied resource ids.
     *
     * @param  string[]  $resourceIds
     */
    public function findMany(array $resourceIds): iterable
    {
        return parent::findMany($resourceIds);
    }

    /**
     * Find the supplied model or throw a runtime exception if it does not exist.
     */
    public function findOrFail(string $resourceId): object
    {
        return parent::findOrFail($resourceId);
    }

    /**
     * Does a model with the supplied resource id exist?
     */
    public function exists(string $resourceId): bool
    {
        return parent::exists($resourceId);
    }
}
