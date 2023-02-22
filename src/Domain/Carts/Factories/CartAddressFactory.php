<?php

namespace Dystcz\LunarApi\Domain\Carts\Factories;

use Dystcz\LunarApi\Domain\Carts\Models\CartAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Lunar\Database\Factories\CartAddressFactory as LunarCartAddressFactory;

class CartAddressFactory extends LunarCartAddressFactory
{
    protected $model = CartAddress::class;
}
