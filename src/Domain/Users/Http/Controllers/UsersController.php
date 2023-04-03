<?php

namespace Dystcz\LunarApi\Domain\Users\Http\Controllers;

use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;
use Dystcz\LunarApi\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;

class UsersController extends Controller
{
    // use Actions\FetchMany;
    // use Actions\FetchOne;

    // use Actions\Store;
    // use Actions\Update;
    // use Actions\Destroy;
    use FetchRelated;
    use FetchRelationship;

    // use Actions\UpdateRelationship;
    // use Actions\AttachRelationship;
    // use Actions\DetachRelationship;
}
