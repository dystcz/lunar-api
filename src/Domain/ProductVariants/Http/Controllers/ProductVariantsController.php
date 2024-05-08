<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\ProductVariants\Contracts\ProductVariantsController as ProductVariantsControllerContract;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchMany;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelated;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchRelationship;

class ProductVariantsController extends Controller implements ProductVariantsControllerContract
{
    use FetchMany;
    use FetchOne;
    use FetchRelated;
    use FetchRelationship;
}
