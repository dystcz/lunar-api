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
        ->expects('order_lines')
        ->get(serverUrl('/order_lines'));

    $response->assertErrorStatus([
        'status' => '404',
        'title' => 'Not Found',
    ]);

})->group('order_lines', 'policies');
