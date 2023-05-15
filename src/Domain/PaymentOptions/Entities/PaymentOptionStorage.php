<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Entities;

use Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class PaymentOptionStorage
{
    /**
     * @var Collection<PaymentOption> $paymentOptions
     */
    private Collection $paymentOptions;

    public function __construct()
    {
        $this->paymentOptions = collect(Config::get('lunar.payments.types'))
            ->map(
                fn (array $paymentOption, string $key) => PaymentOption::fromArray([
                    'id' => $paymentOption['driver'],
                    'driver' => $paymentOption['driver'],
                    'name' => $key,
                ])
            );
    }

    /**
     * Find a shipping option.
     */
    public function find(string $id): ?PaymentOption
    {
        return $this->paymentOptions->firstWhere('id', $id);
    }

    public function cursor(): Generator
    {
        foreach ($this->paymentOptions as $shippingOption) {
            yield $shippingOption;
        }
    }

    /**
     * Get all paymentOptions.
     */
    public function all(): array
    {
        return iterator_to_array($this->cursor());
    }
}
