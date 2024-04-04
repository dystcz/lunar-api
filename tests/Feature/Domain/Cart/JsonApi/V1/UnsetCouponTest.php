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

test('a user can unset a coupon from cart', function () {

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
        ->post('/api/v1/carts/-actions/set-coupon');

    // ray($response->json()['data']['attributes']['prices'])->purple();

    $response->assertSuccessful();

    $this->assertDatabaseHas($cart->getTable(), [
        'id' => $cart->id,
        'coupon_code' => 'AHOJ',
    ]);

    $response = $this
        ->jsonApi()
        ->expects('carts')
        ->withData([
            'type' => 'carts',
            'attributes' => [],
        ])
        ->post('/api/v1/carts/-actions/unset-coupon');

    $response->assertSuccessful();

    $this->assertDatabaseHas($cart->getTable(), [
        'id' => $cart->id,
        'coupon_code' => null,
    ]);

})->group('coupons');
