<?php

namespace Dystcz\LunarApi\Domain\Payments\PaymentAdapters;

class PaymentIntent
{
    public function __construct(
        public readonly int|string $id,
        public readonly int $amount,
        public readonly string $status,
        public readonly ?string $client_secret = null,
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'status' => $this->status,
            'client_secret' => $this->client_secret,
        ];
    }
}
