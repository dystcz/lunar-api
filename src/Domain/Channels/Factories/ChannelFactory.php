<?php

namespace Dystcz\LunarApi\Domain\Channels\Factories;

use Dystcz\LunarApi\Domain\Channels\Models\Channel;
use Lunar\Database\Factories\ChannelFactory as LunarChannelFactory;

class ChannelFactory extends LunarChannelFactory
{
    protected $model = Channel::class;

    public function definition(): array
    {
        return [
            ...parent::definition(),
        ];
    }
}
