<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Domain\Orders\Models\Order;
use Dystcz\LunarApi\Routing\Middleware\TokenBasedCartSessionMiddleware;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Lunar\Facades\CartSession;

uses(TestCase::class, RefreshDatabase::class);

test('if cart is created and returns a token', function () {

    $middleware = new TokenBasedCartSessionMiddleware;

    $request = (new Request)->setRouteResolver(fn () => Route::getRoutes()->getByName('v1.carts.myCart'));

    $response = $middleware->handle($request, fn () => new Illuminate\Http\Response);

    expect($response->headers->get('X-Cart-Token'))->toBeString();
});

test('if a new cart is created when the order is placed', function () {

    $token = Str::random(40);

    $cart = Cart::factory()->withLines()->create([
        'meta' => [
            'token' => $token,
        ],
    ]);

    $order = Order::factory()->create([
        'cart_id' => $cart->id,
    ]);

    $order->placed_at = now();
    $order->save();

    $middleware = new TokenBasedCartSessionMiddleware;

    $request = (new Request)->setRouteResolver(fn () => Route::getRoutes()->getByName('v1.carts.myCart'));

    $request->headers->set('X-Cart-Token', $token);

    $response = $middleware->handle($request, fn ($request) => new Illuminate\Http\Response);

    $this->assertNotEquals($token, $response->headers->get('X-Cart-Token'));
});

test('it should create a new cart and return the token if cart is not found by token', function () {
    $token = Str::random(40);

    $middleware = new TokenBasedCartSessionMiddleware;

    $request = (new Request)->setRouteResolver(fn () => Route::getRoutes()->getByName('v1.carts.myCart'));

    $request->headers->set('X-Cart-Token', $token);

    $response = $middleware->handle($request, fn ($request) => new Illuminate\Http\Response);

    expect($response->headers->get('X-Cart-Token'))->not()->toEqual($token);
});

test('it should use the cart as the current cart if the cart associated with the token does not have an order placed', function () {
    $token = Str::random(40);

    $cart = Cart::factory()->create([
        'meta' => [
            'token' => $token,
        ],
    ]);

    $middleware = new TokenBasedCartSessionMiddleware;

    $request = (new Request)->setRouteResolver(fn () => Route::getRoutes()->getByName('v1.carts.myCart'));

    $request->headers->set('X-Cart-Token', $token);

    $response = $middleware->handle($request, fn ($request) => new Illuminate\Http\Response);

    expect(CartSession::current()->meta['token'])->toEqual($token);
});
