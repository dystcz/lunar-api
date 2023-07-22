<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Lunar\Stripe\Facades\StripeFacade;

class CreateStripePaymentIntent extends CreatePaymentIntent
{
    public function createIntent()
    {
        $intent = StripeFacade::createIntent($this->getCart()->calculate());

        $this->setAmount($intent->amount);
        $this->setDriver('stripe');
        $this->setCardType('unknown');
        $this->setOrderId($this->getCart()->order->id);
        $this->setSuccess(true);
        $this->setReference($intent->id);

        parent::createIntent();
    }
}
