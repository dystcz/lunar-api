<?php

namespace Dystcz\LunarApi\Domain\Customers\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Customers\Contracts\CustomersController as CustomersControllerContract;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Update;

class CustomersController extends Controller implements CustomersControllerContract
{
    use FetchOne;
    use FetchRelated;
    use FetchRelationship;
    use Update;
}
