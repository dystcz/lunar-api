<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('cannot list order lines without order', function () {
    /** @var TestCase $this */
    $cart = Cart::factory()
        ->withLines()
        ->withAddresses()
        ->create();

    $order = $cart->createOrder();

    $response = $this
        ->jsonApi()
        ->expects('order-lines')
        ->get(serverUrl('/order-lines'));

    $response->assertErrorStatus([
        'status' => '404',
        'title' => 'Not Found',
    ]);

})->group('order-lines', 'policies');
