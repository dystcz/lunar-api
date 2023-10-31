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

        $items = array_map(function (array $domain) {
            return new DomainConfig(...$domain);
        }, Config::get('lunar-api.domains'));

        return new static($items);
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
}
