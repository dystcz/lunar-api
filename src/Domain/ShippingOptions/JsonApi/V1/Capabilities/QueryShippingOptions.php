<?php

namespace Dystcz\LunarApi\Domain\ShippingOptions\JsonApi\V1\Capabilities;

use Dystcz\LunarApi\Domain\ShippingOptions\Entities\ShippingOptionStorage;
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
