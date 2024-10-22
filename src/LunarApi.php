<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Base\Concerns;

class LunarApi
{
    use Concerns\HasAuth;
    use Concerns\HashesIds;

    public function getRoot(): string
    {
        return __DIR__;
    }
}
