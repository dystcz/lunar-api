<?php

namespace Dystcz\LunarApi\Domain\ShippingOptions\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\ShippingOptions\Contracts\ShippingOptionsController as ShippingOptionsControllerContract;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;

class ShippingOptionsController extends Controller implements ShippingOptionsControllerContract
{
    use FetchMany;
}
