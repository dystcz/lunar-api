<?php

namespace Dystcz\LunarApi\Domain\Media\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Media\Contracts\MediaController as MediaControllerContract;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\FetchOne;

class MediaController extends Controller implements MediaControllerContract
{
    use FetchOne;
}
