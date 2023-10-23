<?php

namespace Dystcz\LunarApi\Domain\Collections\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;

class CollectionsController extends Controller
{
    use FetchMany;
    use FetchOne;
    use FetchRelated;
    use FetchRelationship;
}
