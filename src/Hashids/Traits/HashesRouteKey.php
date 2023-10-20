<?php

namespace Dystcz\LunarApi\Hashids\Traits;

use Dystcz\LunarApi\Hashids\Facades\HashidsConnections;
use Dystcz\LunarApi\LunarApi;
use Hashids\Hashids;
use Lunar\Facades\ModelManifest;
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
                $this->hashIds()->decode($value)
            )->firstOrFail();
        }

        return $model
            ->newQuery()
            ->where($field, $value)
            ->firstOrFail();
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

        return HashidsConnections::getModelConnection(
            ModelManifest::getMorphClassBaseModel(get_called_class()) ?? get_called_class()
        );
    }
}
