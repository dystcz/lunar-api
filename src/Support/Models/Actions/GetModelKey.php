<?php

namespace Dystcz\LunarApi\Support\Models\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GetModelKey
{
    /**
     * Get model key.
     *
     * @param  Model|class-string  $model
     */
    public function __invoke(Model|string $model): string
    {
        return Str::snake(Str::pluralStudly(class_basename($this)));
    }
}
