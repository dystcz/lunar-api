<?php

namespace Dystcz\LunarApi\Tests\Data;

use Closure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TestInclude
{
    /**
     * @param  Factory<Model>  $factory
     */
    public function __construct(
        public string $type,
        public string $relation,
        public ?Closure $relationCallback = null,
        public ?Factory $factory = null,
        public ?string $factory_relation = null,
        public string $factory_relation_method = 'has',
    ) {
    }

    /**
     * Get relation.
     */
    public function getRelation(Collection|Model $target): Collection|Model|null
    {
        $relationCallback = $this->relationCallback;

        if ($target instanceof Model) {
            /** @var Model $target */
            if ($relationCallback) {
                return $relationCallback($target);
            }

            return $target->{$this->relation};
        }

        /** @var Collection $target */
        if ($relationCallback) {
            return $relationCallback($target);
        }

        return $target->pluck($this->relation)->flatten();
    }
}
