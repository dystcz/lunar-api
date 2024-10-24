<?php

use Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress;
use Dystcz\LunarApi\Domain\Carts\Actions\CreateEmptyCartAddresses;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Customers\Models\Customer;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;

uses(TestCase::class, RefreshDatabase::class)
    ->group('carts', 'cart_addresses');

beforeEach(function () {
    /** @var TestCase $this */
    $this->user = User::factory()
        ->has(Customer::factory())
        ->create();

    $this->cart = Cart::factory()
        ->for($this->user)
        ->withLines()
        ->create();

    $this->cart = (new CreateEmptyCartAddresses)->handle($this->cart);

    /** @property CartSessionManager $cartSession */
    $this->cartSession = App::make(CartSessionInterface::class);

    $this->cartSession->use($this->cart);
});

it('can list related cart addresses', function () {
    /** @var TestCase $this */
    $expected = $this->cart->addresses->map(fn (CartAddress $address) => [
        'type' => 'cart_addresses',
        'id' => (string) $address->getRouteKey(),
        'attributes' => [
            //
        ],
    ])->all();

    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->expects('cart_addresses')
        ->get(serverUrl("/carts/{$this->cart->getRouteKey()}/cart_addresses"));

    $response
        ->assertSuccessful()
        ->assertFetchedMany($expected);

});

it('can read related shipping address', function () {
    /** @var TestCase $this */
    $expected = [
        'type' => 'cart_addresses',
        'id' => (string) $this->cart->shippingAddress->getRouteKey(),
        'attributes' => [
            'address_type' => 'shipping',
        ],
    ];
    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->expects('cart_addresses')
        ->get(serverUrl("/carts/{$this->cart->getRouteKey()}/shipping_address"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($expected);

});

it('can read related billing address', function () {
    /** @var TestCase $this */
    $expected = [
        'type' => 'cart_addresses',
        'id' => (string) $this->cart->billingAddress->getRouteKey(),
        'attributes' => [
            'address_type' => 'billing',
        ],
    ];

    $response = $this
        ->actingAs($this->user)
        ->jsonApi()
        ->expects('cart_addresses')
        ->get(serverUrl("/carts/{$this->cart->getRouteKey()}/billing_address"));

    $response
        ->assertSuccessful()
        ->assertFetchedOne($expected);

});
