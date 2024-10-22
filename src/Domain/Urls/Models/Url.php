<?php

namespace Dystcz\LunarApi\Domain\Urls\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\Urls\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Urls\Contracts\Url as UrlContract;
use Lunar\Models\Contracts\Url as LunarUrlContract;
use Lunar\Models\Url as LunarUrl;

#[ReplaceModel(LunarUrlContract::class)]
class Url extends LunarUrl implements UrlContract
{
    use InteractsWithLunarApi;
}
