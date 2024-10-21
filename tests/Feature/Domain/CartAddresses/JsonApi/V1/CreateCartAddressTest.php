<?php

use Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Countries\Models\Country;
use Dystcz\LunarApi\Facades\LunarApi;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->cart = Cart::factory()->create();

    $this->cartAddress = CartAddress::factory()->make();

    $this->data = [
        'type' => 'cart_addresses',
        'attributes' => [
            'address_type' => $this->cartAddress->type,
            'first_name' => $this->cartAddress->first_name,
            'last_name' => $this->cartAddress->last_name,
            'company_name' => $this->cartAddress->company_name,
            'city' => $this->cartAddress->city,
            'line_one' => $this->cartAddress->line_one,
            'postcode' => $this->cartAddress->postcode,
            'contact_phone' => $this->cartAddress->contact_phone,
            'contact_email' => $this->cartAddress->contact_email,
        ],
        'relationships' => [
            'cart' => [
                'data' => [
                    'type' => 'carts',
                    'id' => (string) $this->cart->getRouteKey(),
                ],
            ],
            'country' => [
                'data' => [
                    'type' => 'countries',
                    'id' => (string) Country::factory()->create()->getRouteKey(),
                ],
            ],
        ],
    ];

    $this->cartSession = App::make(CartSessionInterface::class);
});

test('cart address can be created', function () {
    /** @var TestCase $this */
    $this->cartSession->use($this->cart);

    $response = $this
        ->jsonApi()
        ->expects('cart_addresses')
        ->withData($this->data)
        ->includePaths('cart', 'country')
        ->post(serverUrl('/cart_addresses'));

    $id = $response
        ->assertCreatedWithServerId(serverUrl('/cart_addresses', true), $this->data)
        ->id();

    if (LunarApi::usesHashids()) {
        $id = decodeHashedId($this->cartAddress, $id);
    }

    $this->assertDatabaseHas($this->cartAddress->getTable(), [
        'id' => $id,
    ]);
})->group('cart-addresses');

test('only the user who owns the cart can assign an address to it', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('cart_addresses')
        ->withData($this->data)
        ->includePaths('cart')
        ->post(serverUrl('/cart_addresses'));

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
})->group('cart-addresses');
