<?php

namespace Dystcz\LunarApi\Support\Models\Actions;

use Dystcz\LunarApi\Support\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModelKey extends Action
{
    /**
     * Get model key.
     *
     * @param  Model|class-string  $model
     */
    public static function get(Model|string $model): string
    {
        return Str::snake(class_basename($model));
    }
}
