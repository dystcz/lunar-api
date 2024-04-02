<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Modifiers;

use Illuminate\Support\Collection;

class PaymentModifiers
{
    /**
     * The collection of modifiers to use.
     */
    protected Collection $modifiers;

    /**
     * Initialise the class.
     */
    public function __construct()
    {
        $this->modifiers = collect();
    }

    /**
     * Return the shipping modifiers.
     */
    public function getModifiers(): Collection
    {
        return $this->modifiers;
    }

    /**
     * Add a shipping modifier.
     *
     * @param  class-string  $modifier  Class reference to the modifier.
     */
    public function add(string $modifier): void
    {
        $this->modifiers->push($modifier);
    }

    /**
     * Remove a shipping modifier.
     *
     * @param  class-string  $modifier  Class reference to the modifier.
     */
    public function remove(string $modifier): void
    {
        $this->modifiers->forget($modifier);
    }
}
