<?php

namespace Dystcz\LunarApi\Domain\Products;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class ProductViews
{
    /**
     * Check if tracking product-view is enabled.
     */
    public function enabled(): bool
    {
        $enabled = Config::get('lunar-api.track_product_views');

        return $enabled === true;
    }

    /**
     * Lists of list of products views.
     */
    public function getLists(): array
    {
        return array_map(
            fn ($list) => Str::after($list, 'laravel_database_'),
            Redis::keys('product:views:*')
        );
    }

    /**
     * Sorted list of product ids by most viewed past hour.
     */
    public function sorted(): array
    {
        $lists = $this->getLists();

        $sorted = collect();

        foreach ($lists as $list) {
            $hits = Redis::zCount($list, time() - 60 * 60, time());

            $sorted->push([
                'productId' => (int) Str::afterLast($list, ':'),
                'hits' => $hits,
            ]);
        }

        return $sorted->sortByDesc('hits')->pluck('productId')->toArray();
    }

    /**
     * Record a view.
     */
    public function record(int $productId): void
    {
        if (! $this->enabled()) {
            return;
        }

        Redis::zAdd("product:views:{$productId}", time(), Str::uuid()->toString());

        $this->removeOldEntries();
    }

    /**
     * Removes entries older than an hour.
     */
    public function removeOldEntries(): void
    {
        $lists = $this->getLists();

        foreach ($lists as $list) {
            Redis::zRemRangeByScore($list, 0, time() - 60 * 60);
        }

        // TODO clear empty lists?
    }
}
