<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Illuminate\Support\Facades\App;
use Pixelpillow\LunarMollie\Actions\SetPaymentIssuerOnCart;
use Pixelpillow\LunarMollie\Actions\SetPaymentMethodOnCart;
use Pixelpillow\LunarMollie\Facades\MollieFacade;

class CreateMolliePaymentIntent extends CreatePaymentIntent
{

    /**
     * Mollie payment.
     * @var Mollie\Api\Resources\Payment
     */
    protected $molliePayment;

    public function execute(): void
    {
        App::make(SetPaymentIssuerOnCart::class)($this->getCart(), $this->getMeta()->payment_issuer ?? null);
        App::make(SetPaymentMethodOnCart::class)($this->getCart(), $this->getMeta()->payment_method ?? null);

        $normalizedAmount = MollieFacade::normalizeAmountToString($this->getCart()->calculate()->total->value);

        /** @var Mollie\Api\Resources\Payment $molliePayment */
        $this->molliePayment = MollieFacade::createMolliePayment(
            $this->getCart()->calculate(), $this->getTransaction(),
            $normalizedAmount,
            $this->getMeta()->payment_method ?? '',
            $this->getMeta()->payment_issuer ?? ''
        );

        $this->setAmount($normalizedAmount);
        $this->setDriver('mollie');
        $this->setCardType('mollie-card');
        $this->setOrderId($this->getCart()->order->id);
        $this->setSuccess($this->molliePayment->status === 'paid');
        $this->setReference($this->molliePayment->id);

        parent::createIntent();
    }

    public function response(): array
    {
        return [
            'checkoutUrl' => $this->molliePayment->getCheckoutUrl(),
        ];
    }
}
