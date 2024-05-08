<?php

namespace Dystcz\LunarApi\Domain\Currencies\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Currencies\Contracts\CurrenciesController as CurrenciesControllerContract;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;

class CurrenciesController extends Controller implements CurrenciesControllerContract
{
    use FetchMany;
}
