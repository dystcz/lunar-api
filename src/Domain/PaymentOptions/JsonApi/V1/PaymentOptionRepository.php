<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\JsonApi\V1;

use Dystcz\LunarApi\Domain\PaymentOptions\Entities\PaymentOptionStorage;
use Dystcz\LunarApi\Domain\PaymentOptions\JsonApi\V1\Capabilities\QueryPaymentOptions;
use LaravelJsonApi\Contracts\Store\QueriesAll;
use LaravelJsonApi\NonEloquent\AbstractRepository;

class PaymentOptionRepository extends AbstractRepository implements QueriesAll
{
    private readonly PaymentOptionStorage $storage;

    public function __construct(PaymentOptionStorage $storage)
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
    public function queryAll(): QueryPaymentOptions
    {
        return QueryPaymentOptions::make()
            ->withServer($this->server)
            ->withSchema($this->schema);
    }
}
