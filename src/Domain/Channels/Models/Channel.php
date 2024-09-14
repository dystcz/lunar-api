<?php

namespace Dystcz\LunarApi\Domain\Channels\Models;

use Dystcz\LunarApi\Domain\Channels\Contracts\Channel as ChannelContract;
use Dystcz\LunarApi\Domain\Channels\Factories\ChannelFactory;
use Lunar\Models\Channel as LunarChannel;

class Channel extends LunarChannel implements ChannelContract
{
    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): ChannelFactory
    {
        return ChannelFactory::new();
    }
}
