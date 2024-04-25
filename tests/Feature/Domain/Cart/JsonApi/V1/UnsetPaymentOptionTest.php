<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->cartSession = App::make(CartSessionInterface::class);

    $this->cart = Cart::factory()->create([
        'payment_option' => 'paypal',
    ]);
});

test('users can unset a payment option from cart', function () {
    /** @var TestCase $this */
    $this->cartSession->use($this->cart);

    $response = $this
        ->jsonApi()
        ->expects('carts')
        ->withData([
            'type' => 'carts',
        ])
        ->post(serverUrl('/carts/-actions/unset-payment-option'));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($this->cart);

    $this->assertDatabaseHas($this->cart->getTable(), [
        'payment_option' => null,
    ]);

    expect($this->cart->fresh()->payment_option)->toBeNull();
})->group('carts', 'payment-options');
