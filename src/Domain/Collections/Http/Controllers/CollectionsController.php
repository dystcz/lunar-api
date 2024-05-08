<?php

namespace Dystcz\LunarApi\Domain\Collections\Http\Controllers;

use Dystcz\LunarApi\Domain\Collections\Contracts\CollectionsController as CollectionsControllerContract;
use Illuminate\Routing\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;

class CollectionsController extends Controller implements CollectionsControllerContract
{
    use FetchMany;
    use FetchOne;
    use FetchRelated;
    use FetchRelationship;
}
