<?php

namespace Dystcz\LunarApi\Domain\Users\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Users\Contracts\UsersController as UsersControllerContract;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;

class UsersController extends Controller implements UsersControllerContract
{
    use FetchRelated;
    use FetchRelationship;
}
