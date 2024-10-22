<?php

namespace Dystcz\LunarApi\Domain\Tags\Models;

use Dystcz\LunarApi\Base\Attributes\ReplaceModel;
use Dystcz\LunarApi\Domain\Tags\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Tags\Contracts\Tag as TagContract;
use Lunar\Models\Contracts\Tag as LunarTagContract;
use Lunar\Models\Tag as LunarTag;

#[ReplaceModel(LunarTagContract::class)]
class Tag extends LunarTag implements TagContract
{
    use InteractsWithLunarApi;
}
