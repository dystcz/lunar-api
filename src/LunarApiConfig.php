<?php

namespace Dystcz\LunarApi;

use Dystcz\LunarApi\Support\Config\Collections\DomainConfigCollection;
use Dystcz\LunarApi\Support\Config\Data\DomainConfig;
use Illuminate\Support\Collection;

class LunarApiConfig
{
    public DomainConfigCollection $config;

    /** @param array<string,DomainConfig> $domains */
    public array $domains = [];

    public function __construct()
    {
        $this->config = DomainConfigCollection::make();
    }

    public function domain(string $domain): DomainConfig
    {
        return $this->config->get($domain);
    }

    /**
     * @param  string[]  $domains
     */
    public function domains(...$domains): DomainConfig
    {
        return $this->config->only($domains);
    }

    public function getSchemas(): Collection
    {
        return $this->config->getSchemas();
    }

    public function getRoutes(): Collection
    {
        return $this->config->getRoutes();
    }

    public function getModels(): Collection
    {
        return $this->config->getModelsForModelManifest();
    }

    public function getPolicies(): Collection
    {
        return $this->config->getPolicies();
    }
}
