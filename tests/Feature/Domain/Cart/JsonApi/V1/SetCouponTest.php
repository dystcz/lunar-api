<?php

use Carbon\Carbon;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Discounts\Factories\DiscountFactory;
use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\DiscountTypes\AmountOff;
use Lunar\Facades\CartSession;
use Lunar\Models\Channel;
use Lunar\Models\Currency;
use Lunar\Models\CustomerGroup;

uses(TestCase::class, RefreshDatabase::class);

// function showCartPricing(Cart $cart): void
// {
//     $cart->calculate();
//     ray([
//         'sub_total' => $cart->subTotal?->decimal,
//         'total' => $cart->total?->decimal,
//         'shipping_total' => $cart->shippingTotal?->decimal,
//         'tax_total' => $cart->taxTotal?->decimal,
//         'cart_discount_amount' => $cart->cartDiscountAmount?->decimal,
//         'discount_total' => $cart->discountTotal?->decimal,
//         'tax_breakdown' => $cart->taxBreakdown,
//     ]);
// }

test('a user can set a valid fixed value coupon to cart', function () {

    /** @var TestCase $this */
    $currency = Currency::getDefault();

    $customerGroup = CustomerGroup::getDefault();

    $channel = Channel::getDefault();

    $purchasableA = ProductVariantFactory::new()->create();

    Price::factory()->create([
        'price' => 1000, // 10 EUR
        'min_quantity' => 1,
        'currency_id' => $currency->id,
        'priceable_type' => $purchasableA->getMorphClass(),
        'priceable_id' => $purchasableA->id,
    ]);

    $cart = Cart::factory()->create([
        'currency_id' => $currency->id,
        'channel_id' => $channel->id,
    ]);

    $cart->lines()->create([
        'purchasable_type' => $purchasableA->getMorphClass(),
        'purchasable_id' => $purchasableA->id,
        'quantity' => 10,
    ]);

    $discount = DiscountFactory::new()->create([
        'type' => AmountOff::class,
        'name' => 'Test Coupon',
        'coupon' => 'AHOJ',
        'data' => [
            'fixed_value' => true,
            'fixed_values' => [
                'EUR' => 10,
            ],
        ],
    ]);

    $discount->customerGroups()->sync([
        $customerGroup->id => [
            'enabled' => true,
            'starts_at' => Carbon::now(),
        ],
    ]);

    $discount->channels()->sync([
        $channel->id => [
            'enabled' => true,
            'starts_at' => Carbon::now()->subHour(),
        ],
    ]);

    // showCartPricing($cart);

    CartSession::use($cart);

    $response = $this
        ->jsonApi()
        ->expects('carts')
        ->withData([
            'type' => 'carts',
            'attributes' => [
                'coupon_code' => 'ahoj',
            ],
        ])
        ->post('/api/v1/carts/-actions/set-coupon');

    // ray($response->json()['data']['attributes']['prices'])->purple();

    $response->assertSuccessful();

    $this->assertDatabaseHas($cart->getTable(), [
        'id' => $cart->id,
        'coupon_code' => 'AHOJ',
    ]);

    $cart = CartSession::current();

    $this->assertEquals(10 * 100, $cart->discountTotal->value);
    $this->assertEquals(100 * 100, $cart->subTotal->value);
    $this->assertEquals(90 * 100, $cart->subTotalDiscounted->value);
    $this->assertEquals(108 * 100, $cart->total->value);
    $this->assertEquals(18 * 100, $cart->taxTotal->value);
    $this->assertCount(1, $cart->discounts);
})->group('coupons');

test('a user can set a valid percentage coupon to cart', function () {
    /** @var TestCase $this */
    $currency = Currency::getDefault();

    $customerGroup = CustomerGroup::getDefault();

    $channel = Channel::getDefault();

    $purchasableA = ProductVariantFactory::new()->create();

    Price::factory()->create([
        'price' => 2000, // 20 EUR
        'min_quantity' => 1,
        'currency_id' => $currency->id,
        'priceable_type' => $purchasableA->getMorphClass(),
        'priceable_id' => $purchasableA->id,
    ]);

    $cart = Cart::factory()->create([
        'currency_id' => $currency->id,
        'channel_id' => $channel->id,
    ]);

    $cart->lines()->create([
        'purchasable_type' => $purchasableA->getMorphClass(),
        'purchasable_id' => $purchasableA->id,
        'quantity' => 10,
    ]);

    $discount = DiscountFactory::new()->create([
        'type' => AmountOff::class,
        'name' => 'Test Coupon',
        'coupon' => 'SWAG10',
        'data' => [
            'fixed_value' => false,
            'percentage' => 10,
        ],
    ]);

    $discount->customerGroups()->sync([
        $customerGroup->id => [
            'enabled' => true,
            'starts_at' => Carbon::now(),
        ],
    ]);

    $discount->channels()->sync([
        $channel->id => [
            'enabled' => true,
            'starts_at' => Carbon::now()->subHour(),
        ],
    ]);

    CartSession::use($cart);

    $response = $this
        ->jsonApi()
        ->expects('carts')
        ->withData([
            'type' => 'carts',
            'attributes' => [
                'coupon_code' => 'swag10',
            ],
        ])
        ->post('/api/v1/carts/-actions/set-coupon');

    $response->assertSuccessful();

    $this->assertDatabaseHas($cart->getTable(), [
        'id' => $cart->id,
        'coupon_code' => 'SWAG10',
    ]);

    $cart = CartSession::current();

    $this->assertEquals(20 * 100, $cart->discountTotal->value);
    $this->assertEquals(200 * 100, $cart->subTotal->value);
    $this->assertEquals(180 * 100, $cart->subTotalDiscounted->value);
    $this->assertEquals(216 * 100, $cart->total->value);
    $this->assertEquals(36 * 100, $cart->taxTotal->value);
    $this->assertCount(1, $cart->discounts);
})->group('coupons');
