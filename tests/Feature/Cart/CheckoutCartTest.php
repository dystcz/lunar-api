<?php

use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Lunar\Facades\CartSession;

uses(TestCase::class, RefreshDatabase::class);

test('a user can checkout a cart', function () {
    Event::fake(CartCreated::class);

    /** @var Cart $cart */
    $cart = Cart::factory()
        ->withAddresses()
        ->withLines()
        ->create();

    CartSession::use($cart);

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->withData([
            'type' => 'carts',
            'attributes' => [
                'create_user' => false,
            ],
        ])
        ->post('/api/v1/carts/-actions/checkout');

    $id = $response
        ->assertCreatedWithServerId('http://localhost/api/v1/orders', [])
        ->id();

    $this->assertDatabaseHas((new Order())->getTable(), [
        'id' => $id,
    ]);

    expect($cart->user_id)->toBeNull();
})->group('checkout');

test('a user can be registered when checking out', function () {
    Event::fake([CartCreated::class, Registered::class]);

    /** @var Cart $cart */
    $cart = Cart::withoutEvents(function () {
        return Cart::factory()
            ->withAddresses()
            ->withLines()
            ->create();
    });

    CartSession::use($cart);

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->withData([
            'type' => 'carts',
            'attributes' => [
                'create_user' => true,
            ],
        ])
        ->post('/api/v1/carts/-actions/checkout');

    $id = $response
        ->assertCreatedWithServerId('http://localhost/api/v1/orders', [])
        ->id();

    $this->assertDatabaseHas((new Order())->getTable(), [
        'id' => $id,
    ]);

    $order = Order::query()
        ->where('id', $id)
        ->first();

    Event::assertDispatched(Registered::class, fn (Registered $event) => $event->user->id === $order->user_id);

    expect($order->user_id)->not()->toBeNull();
})->group('checkout');

it('returns signed url for reading order\'s detail', function () {
    Event::fake(CartCreated::class);

    /** @var Cart $cart */
    $cart = Cart::factory()
        ->withAddresses()
        ->withLines()
        ->create();

    CartSession::use($cart);

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->withData([
            'type' => 'carts',
            'attributes' => [
                'create_user' => false,
            ],
        ])
        ->post('/api/v1/carts/-actions/checkout');

    $id = $response->json()['data']['id'];

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->get($response->json()['links']['self.signed']);

})->group('checkout');
