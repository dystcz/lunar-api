<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Lunar\Stripe\Facades\StripeFacade;

class CreateStripePaymentIntent extends CreatePaymentIntent
{
    /**
     * Stripe payment.
     *
     * @var \Stripe\PaymentIntent
     */
    protected $intent;

    public function execute(): void
    {
        $this->intent = StripeFacade::createIntent($this->getCart()->calculate());

        $this->setAmount($this->intent->amount);
        $this->setDriver('stripe');
        $this->setCardType('unknown');
        $this->setOrderId($this->getCart()->order->id);
        $this->setSuccess(true);
        $this->setReference($this->intent->id);

        parent::createIntent();
    }

    public function response(): array
    {
        return $this->intent->toArray();
    }
}
