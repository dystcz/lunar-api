<?php

namespace Dystcz\LunarApi\Domain\Customers\Factories;

use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Domain\OrderLines\Models\OrderLine;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Illuminate\Support\Str;

class CustomerFactory extends \Lunar\Database\Factories\CustomerFactory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'company_name' => $this->faker->boolean ? $this->faker->company : null,
            'vat_no' => $this->faker->boolean ? Str::random() : null,
            'meta' => $this->faker->boolean ? ['account_no' => Str::random()] : null,
        ];
    }

    public function withOrder(): static
    {
        return $this->has(
            Order::factory()
                ->has(
                    OrderLine::factory()
                        ->for(ProductVariant::factory()->withPrice(), 'purchasable'),
                    'lines'
                )
        );
    }
}
