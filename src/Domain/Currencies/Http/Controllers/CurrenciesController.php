<?php

namespace Dystcz\LunarApi\Domain\Currencies\Http\Controllers;

use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use Dystcz\LunarApi\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;

class CurrenciesController extends Controller
{
    use FetchMany;
}
