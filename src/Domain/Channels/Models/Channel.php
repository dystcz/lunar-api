<?php

namespace Dystcz\LunarApi\Domain\Channels\Models;

use Dystcz\LunarApi\Domain\Channels\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Channels\Contracts\Channel as ChannelContract;
use Lunar\Models\Channel as LunarChannel;

class Channel extends LunarChannel implements ChannelContract
{
    use InteractsWithLunarApi;
}
