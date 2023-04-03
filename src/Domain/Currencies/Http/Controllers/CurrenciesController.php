<?php

namespace Dystcz\LunarApi\Domain\Currencies\Http\Controllers;

use Dystcz\LunarApi\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;

class CurrenciesController extends Controller
{
    use FetchMany;
}
