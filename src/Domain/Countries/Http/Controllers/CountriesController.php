<?php

namespace Dystcz\LunarApi\Domain\Countries\Http\Controllers;

use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use Dystcz\LunarApi\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;

class CountriesController extends Controller
{
    use FetchMany;
}
