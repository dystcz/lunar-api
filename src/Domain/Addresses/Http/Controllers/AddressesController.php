<?php

namespace Dystcz\LunarApi\Domain\Addresses\Http\Controllers;

use Dystcz\LunarApi\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Destroy;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Store;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Update;

class AddressesController extends Controller
{
    use FetchMany;
    use FetchOne;
    use Store;
    use Update;
    use Destroy;

    // use Actions\FetchRelated;
    // use Actions\FetchRelationship;

    // use Actions\UpdateRelationship;
    // use Actions\AttachRelationship;
    // use Actions\DetachRelationship;
}
