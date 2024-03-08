<?php

use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('cannot list cart lines without cart', function () {
    /** @var TestCase $this */
    $cart = Cart::factory()->withLines()->create();

    $response = $this
        ->jsonApi()
        ->expects('cart-lines')
        ->get(serverUrl('/cart-lines'));

    $response->assertErrorStatus([
        'status' => '405',
        'title' => 'Method Not Allowed',
    ]);

})->group('cart-lines', 'policies');
