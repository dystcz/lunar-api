<?php

namespace Dystcz\LunarApi\Domain\Payments\PaymentAdapters;

use Illuminate\Support\Facades\App;

class PaymentAdaptersRegister
{
    /**
     * @var class-string<PaymentAdapter>[]
     */
    protected array $adapters = [];

    /**
     * @param  class-string<PaymentAdapter>  $adapter
     */
    public function add(string $driver, string $adapter): void
    {
        $this->adapters[$driver] = $adapter;
    }

    public function has(string $driver): bool
    {
        return array_key_exists($driver, $this->adapters);
    }

    public function get(string $driver): PaymentAdapter
    {
        if (! $this->has($driver)) {
            throw new \RuntimeException('Payment adapter for ['.$driver.'] is not registered');
        }

        return App::make($this->adapters[$driver]);
    }
}
