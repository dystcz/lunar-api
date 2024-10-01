<?php

namespace Dystcz\LunarApi\Domain\Channels\Concerns;

use Dystcz\LunarApi\Domain\Channels\Factories\ChannelFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;

trait InteractsWithLunarApi
{
    use HashesRouteKey;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): ChannelFactory
    {
        return ChannelFactory::new();
    }
}
