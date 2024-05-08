<?php

namespace Dystcz\LunarApi\Domain\Tags\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Tags\Contracts\TagsController as TagsControllerContract;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;

class TagsController extends Controller implements TagsControllerContract
{
    use FetchMany;
    use FetchOne;
    use FetchRelated;
    use FetchRelationship;
}
