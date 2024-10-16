<?php

namespace Dystcz\LunarApi\Hashids\Traits;

use Dystcz\LunarApi\Facades\LunarApi;
use Dystcz\LunarApi\Hashids\Facades\HashidsConnections;
use Hashids\Hashids;
use Illuminate\Support\Arr;
use Vinkla\Hashids\Facades\Hashids as HashidsFacade;

trait HashesRouteKey
{
    /**
     * Get the value of the model's route key.
     */
    public function getRouteKey(): string
    {
        if (! LunarApi::usesHashids()) {
            return parent::getRouteKey();
        }

        /** @var \Lunar\Base\BaseModel $model */
        $model = $this;

        return $this->hashIds()->encode($model->getAttribute($model->getRouteKeyName()));
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if (! LunarApi::usesHashids()) {
            return parent::resolveRouteBinding($value, $field);
        }

        /** @var \Lunar\Base\BaseModel $model */
        $model = $this;

        if (empty($field) || $field === $model->getRouteKeyName()) {
            return $model->newQuery()->where(
                $field ?: $model->getRouteKeyName(),
                $this->decodedRouteKey($value)
            )->firstOrFail();
        }

        return $model
            ->newQuery()
            ->where($field, $value)
            ->firstOrFail();
    }

    /**
     * Encode routeKey.
     */
    public function encodedRouteKey(mixed $value): string
    {
        return $this->hashIds()->encode($value);
    }

    /**
     * Decode hashed routeKey.
     */
    public function decodedRouteKey(mixed $value): mixed
    {
        return Arr::first($this->hashIds()->decode($value));
    }

    /**
     * Get connection name for Hashids.
     */
    protected function hashIds(): Hashids
    {
        return HashidsFacade::connection($this->getHashidsConnection());
    }

    /**
     * Get connection name for Hashids.
     */
    protected function getHashidsConnection(): string
    {
        /** @var \Lunar\Base\BaseModel $model */
        $model = $this;

        return HashidsConnections::getModelConnection($model->getMorphClass());
    }
}
