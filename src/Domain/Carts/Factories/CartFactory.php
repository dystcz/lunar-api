<?php

namespace Dystcz\LunarApi\Domain\Carts\Factories;

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Products\Factories\ProductFactory;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Lunar\Models\CartAddress;
use Lunar\Models\Channel;
use Lunar\Models\Currency;

class CartFactory extends \Lunar\Database\Factories\CartFactory
{
    protected $model = Cart::class;

    public function withAddresses(): static
    {
        return $this
            ->has(CartAddress::factory()->state(['type' => 'billing']), 'addresses')
            ->has(
                CartAddress::factory()->state([
                    'type' => 'shipping',
                    'shipping_option' => 'Friendly Freight Co.',
                ]),
                'addresses'
            );
    }

    public function withLines(int $count = 1): static
    {
        return $this->has(
            CartLineFactory::new()
                ->for(
                    ProductVariant::factory()->for(ProductFactory::new())->withPrice(),
                    'purchasable'
                )
                ->count($count),
            'lines'
        );
    }

    public function definition(): array
    {
        return [
            'user_id' => null,
            'merged_id' => null,
            'currency_id' => Currency::first() ?? Currency::factory(),
            'channel_id' => Channel::factory(),
            'coupon_code' => $this->faker->boolean ? $this->faker->word : null,
            'completed_at' => null,
            'meta' => [],
        ];
    }
}
