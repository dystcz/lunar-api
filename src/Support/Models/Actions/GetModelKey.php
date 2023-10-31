<?php

namespace Dystcz\LunarApi\Support\Models\Actions;

use Dystcz\LunarApi\Support\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GetModelKey extends Action
{
    /**
     * Get model key.
     *
     * @param  Model|class-string  $model
     */
    public function handle(Model|string $model): string
    {
        return Str::snake(Str::pluralStudly(class_basename($this)));
    }
}
