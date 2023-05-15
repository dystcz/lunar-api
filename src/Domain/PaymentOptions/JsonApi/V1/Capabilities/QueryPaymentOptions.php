<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\JsonApi\V1\Capabilities;

use Dystcz\LunarApi\Domain\PaymentOptions\Entities\PaymentOptionStorage;
use LaravelJsonApi\NonEloquent\Capabilities\QueryAll;

class QueryPaymentOptions extends QueryAll
{
    private readonly PaymentOptionStorage $paymentOptions;

    public function __construct(PaymentOptionStorage $paymentOptions)
    {
        parent::__construct();

        $this->paymentOptions = $paymentOptions;
    }

    /**
     * {@inheritDoc}
     */
    public function get(): iterable
    {
        return $this->paymentOptions->all();
    }
}
