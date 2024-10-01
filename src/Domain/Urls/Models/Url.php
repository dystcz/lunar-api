<?php

namespace Dystcz\LunarApi\Domain\Urls\Models;

use Dystcz\LunarApi\Domain\Urls\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Urls\Contracts\Url as UrlContract;
use Lunar\Models\Url as LunarUrl;

class Url extends LunarUrl implements UrlContract
{
    use InteractsWithLunarApi;
}
