<?php

namespace Dystcz\LunarApi\Domain\Carts\Factories;

use Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress;
use Dystcz\LunarApi\Domain\CartLines\Models\CartLine;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Currencies\Models\Currency;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Lunar\Models\Channel;

class CartFactory extends \Lunar\Database\Factories\CartFactory
{
    protected $model = Cart::class;

    public function withAddresses(array $shippingParams = [], array $billingParams = []): static
    {
        return $this
            ->has(
                CartAddress::factory()->state(array_merge([
                    'type' => 'shipping',
                    'shipping_option' => 'FFCDEL',
                ], $shippingParams)),
                'addresses'
            )
            ->has(
                CartAddress::factory()->state(array_merge([
                    'type' => 'billing',
                ], $billingParams)),
                'addresses'
            );
    }

    public function withLines(int $count = 1): static
    {
        return $this->has(
            CartLine::factory()
                ->for(
                    ProductVariant::factory()->for(Product::factory())->withPrice(),
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
            'currency_id' => Currency::getDefault() ?? Currency::factory(),
            'channel_id' => Channel::factory(),
            'coupon_code' => null,
            'completed_at' => null,
            'meta' => [],
        ];
    }
}
