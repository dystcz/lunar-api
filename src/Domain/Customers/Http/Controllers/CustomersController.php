<?php

namespace Dystcz\LunarApi\Domain\Customers\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Update;

class CustomersController extends Controller
{
    use FetchOne;
    use FetchRelated;
    use FetchRelationship;
    use Update;
}
