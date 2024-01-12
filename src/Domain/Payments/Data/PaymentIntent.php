<?php

namespace Dystcz\LunarApi\Domain\Payments\Data;

class PaymentIntent
{
    public function __construct(
        public readonly mixed $paymentIntent,
    ) {
    }

    /**
     * Get payment intent ID.
     */
    public function getId(): mixed
    {
        return $this->paymentIntent->id;
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
        ];
    }
}
