<?php

namespace Dystcz\LunarApi\Domain\Carts\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lunar\Models\Cart;
use Lunar\Models\ProductVariant;

class CartLineFactory extends Factory
{
    protected $model = \Dystcz\LunarApi\Domain\Carts\Models\CartLine::class;

    public function definition(): array
    {
        return [
            'cart_id' => Cart::factory(),
            'quantity' => $this->faker->numberBetween(0, 1000),
            'purchasable_type' => ProductVariant::class,
            'purchasable_id' => 1,
            'meta' => null,
        ];
    }
}
