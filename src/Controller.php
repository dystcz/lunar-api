<?php

namespace Dystcz\LunarApi;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Laravel Controller Traits.
     */
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
}
