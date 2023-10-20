<?php

namespace Dystcz\LunarApi\Domain\Urls\Models;

use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Lunar\Models\Url as LunarUrl;

class Url extends LunarUrl
{
    use HashesRouteKey;
}
