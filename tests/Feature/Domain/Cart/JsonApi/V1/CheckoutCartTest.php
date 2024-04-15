<?php

use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\LunarApi;
use Dystcz\LunarApi\Tests\Stubs\Users\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Lunar\Base\CartSessionInterface;
use Lunar\Managers\CartSessionManager;

uses(TestCase::class, RefreshDatabase::class);

test('a user can checkout a cart', function () {
    /** @var TestCase $this */

    /** @var CartFactory $factory */
    $factory = Cart::factory();

    /** @var Cart $cart */
    $cart = $factory
        ->withAddresses()
        ->withLines()
        ->create();

    /** @var CartSessionManager $cartSession */
    $cartSession = App::make(CartSessionInterface::class);
    $cartSession->use($cart);

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->withData([
            'type' => 'carts',
            'attributes' => [
                'agree' => true,
                'create_user' => false,
            ],
        ])
        ->post('/api/v1/carts/-actions/checkout');

    $id = $response
        ->assertSuccessful()
        ->assertCreatedWithServerId('http://localhost/api/v1/orders', [])
        ->id();

    if (LunarApi::usesHashids()) {
        $id = decodeHashedId($cart->draftOrder, $id);
    }

    $this->assertDatabaseHas((new Order())->getTable(), [
        'id' => $id,
    ]);

    expect($cart->user_id)->toBeNull();
})->group('checkout');

test('a user cannot checkout a cart if the products are not in stock', function () {
    /** @var TestCase $this */

    /** @var CartFactory $factory */
    $factory = Cart::factory();

    /** @var Cart $cart */
    $cart = $factory
        ->withAddresses()
        ->withLines()
        ->create();

    /** @var CartSessionManager $cartSession */
    $cartSession = App::make(CartSessionInterface::class);
    $cartSession->use($cart);

    $cart->lines->first()->purchasable->update([
        'stock' => 1,
        'purchasable' => 'in_stock',
    ]);

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->withData([
            'type' => 'carts',
            'attributes' => [
                'agree' => true,
                'create_user' => false,
            ],
        ])
        ->post('/api/v1/carts/-actions/checkout');

    $response->assertStatus(422);
})->group('checkout');

test('a user can be registered when checking out', function () {
    /** @var TestCase $this */
    Event::fake([Registered::class]);

    /** @var Cart $cart */
    $cart = Cart::withoutEvents(function () {
        /** @var CartFactory $factory */
        $factory = Cart::factory();

        return $factory
            ->withAddresses()
            ->withLines()
            ->create();
    });

    /** @var CartSessionManager $cartSession */
    $cartSession = App::make(CartSessionInterface::class);
    $cartSession->use($cart);

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->withData([
            'type' => 'carts',
            'attributes' => [
                'agree' => true,
                'create_user' => true,
            ],
        ])
        ->post('/api/v1/carts/-actions/checkout');

    $id = $response
        ->assertSuccessful()
        ->assertCreatedWithServerId('http://localhost/api/v1/orders', [])
        ->id();

    if (LunarApi::usesHashids()) {
        $id = decodeHashedId($cart->draftOrder, $id);
    }

    $this->assertDatabaseHas((new Order())->getTable(), [
        'id' => $id,
    ]);

    $order = Order::query()
        ->where('id', $id)
        ->first();

    Event::assertDispatched(Registered::class, fn (Registered $event) => $event->user->id === $order->user_id);

    expect($order->user_id)->not()->toBeNull();
})->group('checkout');

test('it validates existing user before being registered when checking out', function () {
    /** @var TestCase $this */
    Event::fake([Registered::class]);

    /** @var Cart $cart */
    $cart = Cart::withoutEvents(function () {
        /** @var CartFactory $factory */
        $factory = Cart::factory();

        return $factory
            ->withAddresses()
            ->withLines()
            ->create();
    });

    // Create a user with the same email as the cart's shipping address
    $user = User::factory()->create([
        'email' => $cart->shippingAddress->contact_email,
    ]);

    /** @var CartSessionManager $cartSession */
    $cartSession = App::make(CartSessionInterface::class);
    $cartSession->use($cart);

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->withData([
            'type' => 'carts',
            'attributes' => [
                'agree' => true,
                'create_user' => true,
            ],
        ])
        ->post('/api/v1/carts/-actions/checkout');

    $response
        ->assertStatus(422)
        ->assertErrorStatus([
            'detail' => __('lunar-api::validations.auth.email.unique'),
            'status' => '422',
        ]);

    Event::assertNotDispatched(Registered::class);
})->group('checkout');

it('does not forget cart after checkout if configured', function () {
    /** @var TestCase $this */
    Config::set('lunar-api.general.checkout.forget_cart_after_order_creation', false);

    /** @var CartFactory $factory */
    $factory = Cart::factory();

    /** @var Cart $cart */
    $cart = $factory
        ->withAddresses()
        ->withLines()
        ->create();

    /** @var CartSessionManager $cartSession */
    $cartSession = App::make(CartSessionInterface::class);
    $cartSession->use($cart);

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->withData([
            'type' => 'carts',
            'attributes' => [
                'agree' => true,
                'create_user' => false,
            ],
        ])
        ->post('/api/v1/carts/-actions/checkout');

    $response
        ->assertSuccessful();

    $this->assertTrue(Session::has($cartSession->getSessionKey()));
})->group('checkout');

it('forgets cart after checkout if configured', function () {
    /** @var TestCase $this */
    Config::set('lunar-api.general.checkout.forget_cart_after_order_creation', true);

    /** @var CartFactory $factory */
    $factory = Cart::factory();

    /** @var Cart $cart */
    $cart = $factory
        ->withAddresses()
        ->withLines()
        ->create();

    /** @var CartSessionManager $cartSession */
    $cartSession = App::make(CartSessionInterface::class);
    $cartSession->use($cart);

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->withData([
            'type' => 'carts',
            'attributes' => [
                'agree' => true,
                'create_user' => false,
            ],
        ])
        ->post('/api/v1/carts/-actions/checkout');

    $response
        ->assertSuccessful();

    $this->assertFalse(Session::has($cartSession->getSessionKey()));
})->group('checkout');

it('returns signed urls for order actions', function () {
    /** @var TestCase $this */

    /** @var CartFactory $factory */
    $factory = Cart::factory();

    /** @var Cart $cart */
    $cart = $factory
        ->withAddresses()
        ->withLines()
        ->create();

    /** @var CartSessionManager $cartSession */
    $cartSession = App::make(CartSessionInterface::class);
    $cartSession->use($cart);

    $response = $this
        ->jsonApi()
        ->expects('orders')
        ->withData([
            'type' => 'carts',
            'attributes' => [
                'agree' => true,
                'create_user' => false,
            ],
        ])
        ->post('/api/v1/carts/-actions/checkout');

    $response
        ->assertSuccessful()
        ->assertLinks([
            'self.signed' => $response->json()['links']['self.signed'],
            'create-payment-intent.signed' => $response->json()['links']['create-payment-intent.signed'],
            'mark-order-pending-payment.signed' => $response->json()['links']['mark-order-pending-payment.signed'],
            'mark-order-awaiting-payment.signed' => $response->json()['links']['mark-order-awaiting-payment.signed'],
            'check-order-payment-status.signed' => $response->json()['links']['check-order-payment-status.signed'],
        ]);
})->group('checkout');
