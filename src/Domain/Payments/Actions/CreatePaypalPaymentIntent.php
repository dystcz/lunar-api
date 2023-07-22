<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Dystcz\LunarPaypal\Data\Order as PaypalOrder;
use Dystcz\LunarPaypal\Facades\PaypalFacade;

class CreatePaypalPaymentIntent extends CreatePaymentIntent
{
    public function createIntent()
    {
        /** @var PaypalOrder $intent */
        $intent = PaypalFacade::createIntent($this->getCart()->calculate());

        $this->setAmount($intent->totalAmount());
        $this->setDriver('paypal');
        $this->setCardType('paypal');
        $this->setOrderId($this->getCart()->order->id);
        $this->setSuccess(true);
        $this->setReference($intent->id);

        parent::createIntent();
    }
}
