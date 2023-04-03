<?php

namespace Dystcz\LunarApi\Domain\Shipping\JsonApi\V1\Capabilities;

use Dystcz\LunarApi\Domain\Shipping\Entities\ShippingOptionStorage;
use LaravelJsonApi\NonEloquent\Capabilities\QueryAll;

class QueryShippingOptions extends QueryAll
{
    private readonly ShippingOptionStorage $sites;

    public function __construct(ShippingOptionStorage $sites)
    {
        parent::__construct();

        $this->sites = $sites;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): iterable
    {
        return $this->sites->all();
    }
}
