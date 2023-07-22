<?php

namespace Dystcz\LunarApi\Domain\Payments\Actions;

use Illuminate\Support\Facades\App;
use Pixelpillow\LunarMollie\Actions\SetPaymentIssuerOnCart;
use Pixelpillow\LunarMollie\Actions\SetPaymentMethodOnCart;
use Pixelpillow\LunarMollie\Facades\MollieFacade;

class CreateMolliePaymentIntent extends CreatePaymentIntent
{
    public function createIntent()
    {
        App::make(SetPaymentIssuerOnCart::class)($this->getCart(), $this->getMeta()->payment_issuer ?? null);
        App::make(SetPaymentMethodOnCart::class)($this->getCart(), $this->getMeta()->payment_method ?? null);

        /** @var Mollie\Api\Resources\Payment $intent */
        $normalizedAmount = MollieFacade::normalizeAmountToString($this->getCart()->calculate()->total->value);
        $intent = MollieFacade::createMolliePayment($this->getCart()->calculate(), $this->getTransaction(), $normalizedAmount);

        $this->setAmount($normalizedAmount);
        $this->setDriver('mollie');
        $this->setCardType($intent->method);
        $this->setOrderId($this->getCart()->order->id);
        $this->setSuccess($intent->status === 'paid');
        $this->setReference($intent->id);

        parent::createIntent();
    }
}
