<?php

namespace Dystcz\LunarApi\Domain\CartAddresses\Factories;

use Lunar\Database\Factories\CartAddressFactory as LunarCartAddressFactory;
use Lunar\Models\Country;

class CartAddressFactory extends LunarCartAddressFactory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'company_name' => $this->faker->company,
            'line_one' => $this->faker->streetName,
            'line_two' => $this->faker->secondaryAddress,
            'line_three' => $this->faker->buildingNumber,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'postcode' => $this->faker->postcode,
            'delivery_instructions' => $this->faker->sentence,
            'contact_email' => $this->faker->safeEmail,
            'contact_phone' => $this->faker->phoneNumber,
            'type' => 'shipping',
            'meta' => ['has_dog' => 'yes'],
            'country_id' => Country::modelClass()::first() ?? Country::modelClass()::factory(),
        ];
    }
}
