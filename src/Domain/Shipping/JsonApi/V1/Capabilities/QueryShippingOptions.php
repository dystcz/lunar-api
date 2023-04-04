<?php

namespace Dystcz\LunarApi\Domain\Shipping\JsonApi\V1\Capabilities;

use Dystcz\LunarApi\Domain\Shipping\Entities\ShippingOptionStorage;
use LaravelJsonApi\NonEloquent\Capabilities\QueryAll;

class QueryShippingOptions extends QueryAll
{
    private readonly ShippingOptionStorage $shippingOptions;

    public function __construct(ShippingOptionStorage $shippingOptions)
    {
        parent::__construct();

        $this->shippingOptions = $shippingOptions;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): iterable
    {
        return $this->shippingOptions->all();
    }
}
