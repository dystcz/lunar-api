<?php

namespace Dystcz\LunarApi\Domain\Filters\JsonApi\V1;

use Dystcz\LunarApi\Domain\Filters\Entities\FilterStorage;
use Dystcz\LunarApi\Domain\Filters\JsonApi\V1\Capabilities\QueryFilters;
use LaravelJsonApi\Contracts\Store\QueriesAll;
use LaravelJsonApi\NonEloquent\AbstractRepository;

class FilterRepository extends AbstractRepository implements QueriesAll
{
    private readonly FilterStorage $storage;

    public function __construct(FilterStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritDoc}
     */
    public function find(string $resourceId): ?object
    {
        return $this->storage->find($resourceId);
    }

    /**
     * {@inheritDoc}
     */
    public function queryAll(): QueryFilters
    {
        return QueryFilters::make()
            ->withServer($this->server)
            ->withSchema($this->schema);
    }
}
