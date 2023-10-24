<?php

namespace Dystcz\LunarApi\Domain\CartLines\Factories;

use Dystcz\LunarApi\Domain\CartLines\Models\CartLine;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Lunar\Database\Factories\CartLineFactory as LunarCartLineFactory;

/**
 * @extends Factory<Model>
 */
class CartLineFactory extends LunarCartLineFactory
{
    protected $model = CartLine::class;

    public function definition(): array
    {
        return [
            'cart_id' => Cart::factory(),
            'quantity' => $this->faker->numberBetween(0, 100),
            'purchasable_type' => ProductVariant::class,
            'purchasable_id' => 1,
            'meta' => null,
        ];
    }
}
