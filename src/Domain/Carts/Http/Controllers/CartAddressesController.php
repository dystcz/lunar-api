<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;

class CartAddressesController extends Controller
{
    use Actions\Store;
    use Actions\Update;
}
