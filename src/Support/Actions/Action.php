<?php

namespace Dystcz\LunarApi\Support\Actions;

use Exception;

/**
 * @method static mixed run(...$args)
 * @method mixed __invoke(...$args)
 */
abstract class Action
{
    /**
     * Check if handle method exists.
     *
     * @throws Exception
     */
    private function checkIfHandleExists(): void
    {
        if (! method_exists($this, 'handle')) {
            throw new Exception('Action must have handle method.');
        }
    }

    /**
     * Invoke the action.
     *
     * @param  mixed  $args
     */
    public function __invoke(...$args): mixed
    {
        $this->checkIfHandleExists();

        return call_user_func_array([$this, 'handle'], $args);
    }

    /**
     * Run the action.
     *
     * @param  mixed  $args
     */
    public static function run(...$args)
    {
        $self = new static;

        $self->checkIfHandleExists();

        return call_user_func_array([$self, 'handle'], $args);
    }
}
