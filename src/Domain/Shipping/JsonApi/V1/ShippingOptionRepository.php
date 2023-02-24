<?php

namespace Dystcz\LunarApi\Domain\Shipping\JsonApi\V1;

use Dystcz\LunarApi\Domain\Shipping\Entities\ShippingOptionStorage;
use LaravelJsonApi\Contracts\Store\QueriesAll;
use LaravelJsonApi\NonEloquent\AbstractRepository;

class ShippingOptionRepository extends AbstractRepository implements QueriesAll
{
    private ShippingOptionStorage $storage;

    public function __construct(ShippingOptionStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
    public function find(string $resourceId): ?object
    {
        return $this->storage->find($resourceId);
    }

    /**
     * @inheritDoc
     */
    public function queryAll(): Capabilities\QueryShippingOptions
    {
        return Capabilities\QueryShippingOptions::make()
            ->withServer($this->server)
            ->withSchema($this->schema);
    }
}