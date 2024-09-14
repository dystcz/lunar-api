<?php

namespace Dystcz\LunarApi\Support\Models\Actions;

use Dystcz\LunarApi\Support\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModelType extends Action
{
    /**
     * Get model key.
     *
     * @param  Model|class-string  $model
     */
    public static function get(Model|string $model, bool $plural = true): string
    {
        $type = Str::kebab(class_basename($model));

        return $plural ? Str::plural($type) : $type;
    }
}
