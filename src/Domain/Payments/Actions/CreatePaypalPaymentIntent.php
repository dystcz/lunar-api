<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Dystcz\LunarPaypal\Data\Order as PaypalOrder;
use Dystcz\LunarPaypal\Facades\PaypalFacade;

class CreatePaypalPaymentIntent extends CreatePaymentIntent
{
    /**
     * Paypal payment.
     *
     * @var PaypalOrder
     */
    protected $paypalPayment;

    public function execute(): void
    {
        /** @var PaypalOrder $intent */
        $this->paypalPayment = PaypalFacade::createIntent($this->getCart()->calculate());

        $this->setAmount($this->paypalPayment->totalAmount());
        $this->setDriver('paypal');
        $this->setCardType('paypal');
        $this->setOrderId($this->getCart()->order->id);
        $this->setSuccess(true);
        $this->setReference($this->paypalPayment->id);

        parent::createIntent();
    }

    public function response(): array
    {
        return $this->paypalPayment->toArray();
    }
}
