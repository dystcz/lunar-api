<?php

namespace Dystcz\LunarApi\Domain\Carts\Factories;

use Dystcz\LunarApi\Domain\Carts\Models\CartAddress;
use Lunar\Database\Factories\CartAddressFactory as LunarCartAddressFactory;
use Lunar\Models\Country;

class CartAddressFactory extends LunarCartAddressFactory
{
    protected $model = CartAddress::class;

    public function definition(): array
    {
        return array_merge(parent::definition(), [
            'contact_email' => $this->faker->safeEmail,
            'country_id' => Country::first()->id ?? Country::factory(),
        ]);
    }
}
