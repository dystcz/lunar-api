<?php

use Dystcz\LunarApi\Domain\Carts\Factories\CartFactory;
use Dystcz\LunarApi\Domain\Users\Models\User;
use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Lunar\Base\CartSessionInterface;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
    $this->cartSession = App::make(CartSessionInterface::class);
});

afterEach(function () {
    /** @var TestCase $this */
    $this->cartSession->forget();
});

it('can create empty cart addresses for a cart', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();

    $cart = CartFactory::new()
        ->for($user)
        ->withLines()
        ->create();

    $this->cartSession->use($cart);

    $response = $this
        ->actingAs($user)
        ->jsonApi()
        ->expects('carts')
        ->withData([
            'type' => 'carts',
        ])
        ->post(serverUrl('/carts/-actions/create-empty-addresses'));

    $cart = $this->cartSession->current();

    $response
        ->assertSuccessful()
        ->assertFetchedOne($cart);

})->group('carts', 'carts-addresses');
