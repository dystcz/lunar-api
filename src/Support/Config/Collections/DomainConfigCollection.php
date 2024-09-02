<?php

namespace Dystcz\LunarApi\Support\Config\Collections;

use Dystcz\LunarApi\Support\Config\Data\DomainConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class DomainConfigCollection extends Collection
{
    /**
     * Create domain config collection.
     */
    public static function make($items = []): self
    {
        if (! empty($items)) {
            return new static($items);
        }

        return self::fromConfig('lunar-api.domains');
    }

    /**
     * Create domain config collection from a given config file.
     */
    public static function fromConfig(string $configKey): self
    {
        $items = array_map(function (array $domain) {
            return new DomainConfig(...$domain);
        }, Config::get($configKey, []));

        return new static($items);
    }

    /**
     * Get schemas from domain config.
     */
    public function getSchemas(): self
    {
        return $this->mapWithKeys(function (DomainConfig $domain) {
            if (! $domain->hasSchema()) {
                return [];
            }

            return [$domain->schema::type() => $domain->schema];
        });
    }

    /**
     * Get routes from domain config.
     */
    public function getRoutes(): self
    {
        return $this->mapWithKeys(function (DomainConfig $domain) {
            if (! $domain->hasRoutes()) {
                return [];
            }

            return [$domain->schema::type() => $domain->routes];
        });
    }

    /**
     * Get models for Lunar model manifest.
     */
    public function getModelsForModelManifest(): self
    {
        return $this->mapWithKeys(function (DomainConfig $domain) {
            if (! $domain->swapsLunarModel()) {
                return [];
            }

            return [$domain->lunar_model => $domain->model];
        });
    }

    /**
     * Get policies from domain config.
     */
    public function getPolicies(): self
    {
        return $this->mapWithKeys(function (DomainConfig $domain) {
            if (! $domain->hasPolicy()) {
                return [];
            }

            return [$domain->model => $domain->policy];
        });
    }
}
