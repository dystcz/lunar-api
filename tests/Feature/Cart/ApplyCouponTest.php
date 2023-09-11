<?php

use Carbon\Carbon;
use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Discounts\Factories\DiscountFactory;
use Dystcz\LunarApi\Domain\Prices\Models\Price;
use Dystcz\LunarApi\Domain\ProductVariants\Factories\ProductVariantFactory;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
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

test('a user can apply a valid coupon', function () {
    Event::fake(CartCreated::class);

    /** @var TestCase $this */
    $currency = Currency::getDefault();

    $customerGroup = CustomerGroup::getDefault();

    $channel = Channel::getDefault();

    $purchasableA = ProductVariantFactory::new()->create();

    Price::factory()->create([
        'price' => 1000, // 10 EUR
        'tier' => 1,
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
        ->post('/api/v1/carts/-actions/apply-coupon');

    // ray($response->json()['data']['attributes']['prices'])->purple();

    $response->assertSuccessful();

    $this->assertDatabaseHas($cart->getTable(), [
        'id' => $cart->id,
        'coupon_code' => 'AHOJ',
    ]);

    $cart = CartSession::current();

    // FIX: Discounts are not applied
    // DiscountManager first checks cart, because when calling CartSession::use(), cart is calculated with calculate() method
    // this sets $discounts property to empty collection
    // so when calling CartSession::current(), discounts are not applied
    // because then it checks only if $discounts are null
    /** @see Lunar\Managers\DiscountManager */
    $this->assertEquals(1000, $cart->discountTotal->value);
    $this->assertEquals(10000, $cart->subTotal->value);
    $this->assertEquals(9000, $cart->subTotalDiscounted->value);
    $this->assertEquals(10800, $cart->total->value);
    $this->assertEquals(1800, $cart->taxTotal->value);
    $this->assertCount(1, $cart->discounts);
})->group('coupons')->skip('Skip until https://github.com/lunarphp/lunar/pull/1243 is merged');
