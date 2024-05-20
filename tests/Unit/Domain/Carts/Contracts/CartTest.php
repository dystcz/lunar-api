<?php

use Dystcz\LunarApi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('can bind cart contract to model implementation', function () {

})->group('carts', 'carts.model')->todo();
