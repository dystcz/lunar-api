<?php

namespace Dystcz\LunarApi\Domain\Currencies\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;

class CurrenciesController extends Controller
{
    use FetchMany;
}
