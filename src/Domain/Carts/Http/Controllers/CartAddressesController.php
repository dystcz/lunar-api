<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use LaravelJsonApi\Laravel\Http\Controllers\Actions\Store;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Update;
use Dystcz\LunarApi\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;

class CartAddressesController extends Controller
{
    use Store;
    use Update;
}
