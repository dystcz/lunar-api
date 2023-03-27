<?php

use Dystcz\LunarApi\Domain\Carts\Events\CartCreated;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
});

it('a user can be registered when checking out', function () {
    Event::fake([CartCreated::class, Registered::class]);

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

    Event::assertDispatched(Registered::class, function (Registered $event) use ($cart) {
        return $event->user->id === $cart->user_id;
    });

    expect($cart->user_id)->not()->toBeNull();
});