<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;

class ProductVariantsController extends Controller
{
    use FetchMany;
    use FetchRelated;
    use FetchRelationship;
}
