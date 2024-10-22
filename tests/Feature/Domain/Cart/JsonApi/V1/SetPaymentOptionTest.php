<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\PaymentOptions\Facades\PaymentManifest;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->cartSession = App::make(CartSessionInterface::class);

    $this->cart = Cart::factory()->create();

    $this->paymentOption = PaymentManifest::getOptions($this->cart)->first();
});

test('users can set a payment option to cart', function () {
    /** @var TestCase $this */
    $this->cartSession->use($this->cart);

    $data = [
        'type' => 'carts',
        'attributes' => [
            'payment_option' => $this->paymentOption->identifier,
        ],
    ];

    $response = $this
        ->jsonApi()
        ->expects('carts')
        ->withData($data)
        ->post(serverUrl('/carts/-actions/set-payment-option'));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($this->cart);

    $this->assertDatabaseHas($this->cart->getTable(), [
        'payment_option' => $this->paymentOption->identifier,
    ]);

    expect($this->cart->fresh()->payment_option)->toBe($data['attributes']['payment_option']);
})->group('carts', 'payment_options');

it('validates payment option attribute when setting payment option to cart', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('carts')
        ->withData([
            'type' => 'carts',
            'attributes' => [
                'payment_option' => null,
            ],
        ])
        ->post(serverUrl('/carts/-actions/set-payment-option'));

    $response->assertErrorStatus([
        'detail' => __('lunar-api::validations.payments.set_payment_option.payment_option.required'),
        'status' => '422',
    ]);
})->group('carts', 'payment_options');
