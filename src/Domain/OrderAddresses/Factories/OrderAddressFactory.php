<?php

namespace Dystcz\LunarApi\Domain\OrderAddresses\Factories;

use Dystcz\LunarApi\Domain\OrderAddresses\Models\OrderAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Lunar\Database\Factories\OrderAddressFactory as LunarOrderAddressFactory;

/**
 * @extends Factory<Model>
 */
class OrderAddressFactory extends LunarOrderAddressFactory
{
    protected $model = OrderAddress::class;

    public function definition(): array
    {
        return parent::definition();
    }
}
