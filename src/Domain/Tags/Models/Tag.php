<?php

namespace Dystcz\LunarApi\Domain\Tags\Models;

use Dystcz\LunarApi\Domain\Tags\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Tags\Contracts\Tag as TagContract;
use Lunar\Models\Tag as LunarTag;

class Tag extends LunarTag implements TagContract
{
    use InteractsWithLunarApi;
}
