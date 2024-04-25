<?php

namespace Dystcz\LunarApi\Domain\Carts\Validation;

use Lunar\Validation\BaseValidator;

class PaymentOptionValidator extends BaseValidator
{
    /**
     * {@inheritDoc}
     */
    public function validate(): bool
    {
        $cart = $this->parameters['cart'] ?? null;

        if (! $cart) {
            return $this->fail('cart', 'Unable to set payment option on null');
        }

        return $this->pass();
    }
}
