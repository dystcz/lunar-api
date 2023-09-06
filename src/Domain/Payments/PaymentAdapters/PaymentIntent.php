<?php

namespace Dystcz\LunarApi\Domain\Payments\PaymentAdapters;

class PaymentIntent
{
    public function __construct(
        public readonly int|string $id,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
