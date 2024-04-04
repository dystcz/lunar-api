<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Countries\Models\Country;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->cart = Cart::factory()->withAddresses()->create();

    $this->country = Country::factory()->create();

    $this->cartAddress = $this->cart->addresses->first();

    $this->data = [
        'id' => (string) $this->cartAddress->getRouteKey(),
        'type' => 'cart-addresses',
        'relationships' => [
            'country' => [
                'data' => [
                    'type' => 'countries',
                    'id' => (string) $this->country->getRouteKey(),
                ],
            ],
        ],
    ];

    $this->cartSession = App::make(CartSessionInterface::class);
});

test('users can update cart address country', function () {
    /** @var TestCase $this */
    $this->cartSession->use($this->cart);

    $response = $this
        ->jsonApi()
        ->expects('cart-addresses')
        ->withData($this->data)
        ->patch(serverUrl("/cart-addresses/{$this->cartAddress->getRouteKey()}/-actions/update-country"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($this->cartAddress);

    $this->assertDatabaseHas($this->cartAddress->getTable(), [
        'country_id' => $this->country->id,
    ]);

})->group('cart-addresses');

test('only the user who owns the cart address can update its country', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('cart-addresses')
        ->withData($this->data)
        ->patch(serverUrl("/cart-addresses/{$this->cartAddress->getRouteKey()}/-actions/update-country"));

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
})->group('cart-addresses');
