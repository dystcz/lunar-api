<?php

use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can auto create payment intent for configured drivers', function () {

})->group('carts', 'carts.checkout')->todo();
