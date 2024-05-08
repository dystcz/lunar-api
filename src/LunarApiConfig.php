<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Support\Config\Collections\DomainConfigCollection;
use Illuminate\Support\Collection;

class LunarApiConfig
{
    public DomainConfigCollection $config;

    public function __construct()
    {
        $this->config = DomainConfigCollection::make();
    }

    /**
     * Get schemas.
     */
    public function getSchemas(): Collection
    {
        return $this->config->getSchemas();
    }

    /**
     * Get routes.
     */
    public function getRoutes(): Collection
    {
        return $this->config->getRoutes();
    }

    /**
     * Get models for model manifest.
     */
    public function getModels(): Collection
    {
        return $this->config->getModels();
    }

    /**
     * Get policies.
     */
    public function getPolicies(): Collection
    {
        return $this->config->getPolicies();
    }
}
