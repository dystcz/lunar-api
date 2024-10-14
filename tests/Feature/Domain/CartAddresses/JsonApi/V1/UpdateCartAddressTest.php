<?php

use Dystcz\LunarApi\Domain\CartAddresses\Models\CartAddress;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->cart = Cart::factory()->create();

    $this->cartAddress = CartAddress::factory()->for($this->cart)->create();

    $this->data = [
        'id' => (string) $this->cartAddress->getRouteKey(),
        'type' => 'cart_addresses',
        'attributes' => [
            'first_name' => 'Jogn',
            'last_name' => 'Doe',
            'company_name' => 'Acme',
            'city' => 'London',
            'line_one' => '1 Ac',
            'postcode' => '12345',
            'contact_email' => 'email@email.com',
            'contact_phone' => '123456789',
            'delivery_instructions' => 'Leave it at the door',
        ],
    ];

    $this->cartSession = App::make(CartSessionInterface::class);
});

test('a cart address can be update', function () {
    /** @var TestCase $this */
    $this->cartSession->use($this->cart);

    $response = $this
        ->jsonApi()
        ->expects('cart_addresses')
        ->withData($this->data)
        ->patch(serverUrl("/cart_addresses/{$this->cartAddress->getRouteKey()}"));

    $response->assertFetchedOne($this->cartAddress);

    $this->assertDatabaseHas($this->cartAddress->getTable(), [
        'id' => $this->cartAddress->id,
        'first_name' => $this->data['attributes']['first_name'],
        'last_name' => $this->data['attributes']['last_name'],
        'company_name' => $this->data['attributes']['company_name'],
        'city' => $this->data['attributes']['city'],
        'line_one' => $this->data['attributes']['line_one'],
        'postcode' => $this->data['attributes']['postcode'],
        'contact_email' => $this->data['attributes']['contact_email'],
        'contact_phone' => $this->data['attributes']['contact_phone'],
        'delivery_instructions' => $this->data['attributes']['delivery_instructions'],
    ]);
})->group('cart-addresses');

test('only the user who owns the cart can assign address to it', function () {
    /** @var TestCase $this */
    $response = $this
        ->jsonApi()
        ->expects('cart_addresses')
        ->withData($this->data)
        ->patch(serverUrl("/cart_addresses/{$this->cartAddress->getRouteKey()}"));

    $response->assertErrorStatus([
        'detail' => 'Unauthenticated.',
        'status' => '401',
        'title' => 'Unauthorized',
    ]);
})->group('cart-addresses');
