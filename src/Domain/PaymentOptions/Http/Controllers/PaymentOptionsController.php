<?php

namespace Dystcz\LunarApi\Domain\PaymentOptions\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\PaymentOptions\Contracts\PaymentOptionsController as PaymentOptionsControllerContract;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;

class PaymentOptionsController extends Controller implements PaymentOptionsControllerContract
{
    use FetchMany;
}
