<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Support\Config\Collections\DomainConfigCollection;

class LunarApiConfig
{
    public DomainConfigCollection $config;

    public function __construct()
    {
        $this->config = DomainConfigCollection::make();
    }
}
