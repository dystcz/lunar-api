<?php

namespace Dystcz\LunarApi\Domain\Customers\Http\Controllers;

use Dystcz\LunarApi\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Update;

class CustomersController extends Controller
{
    // use Actions\FetchMany;
    use FetchOne;

    // use Actions\Store;
    use Update;

    // use Actions\Destroy;
    use FetchRelated;
    use FetchRelationship;

    // use Actions\UpdateRelationship;
    // use Actions\AttachRelationship;
    // use Actions\DetachRelationship;
}
