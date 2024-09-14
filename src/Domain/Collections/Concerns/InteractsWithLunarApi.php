<?php

namespace Dystcz\LunarApi\Domain\Collections\Concerns;

use Dystcz\LunarApi\Domain\Attributes\Traits\InteractsWithAttributes;
use Dystcz\LunarApi\Domain\Collections\Factories\CollectionFactory;
use Dystcz\LunarApi\Hashids\Traits\HashesRouteKey;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Config;

trait InteractsWithLunarApi
{
    use HashesRouteKey;
    use InteractsWithAttributes;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): CollectionFactory
    {
        return CollectionFactory::new();
    }

    public function images(): MorphMany
    {
        /** @var \Lunar\Models\Collection $model */
        $model = $this;

        return $model
            ->media()
            ->where(
                'collection_name',
                Config::get('lunar.media.collection'),
            );
    }
}
