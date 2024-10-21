<?php

namespace Dystcz\LunarApi\Domain\JsonApi\Eloquent;

use Illuminate\Database\Eloquent\Model;
use LaravelJsonApi\Eloquent\Contracts\Parser;
use LaravelJsonApi\Eloquent\Contracts\Proxy as ProxyContract;
use LaravelJsonApi\Eloquent\Parsers\ProxyParser;

abstract class ProxySchema extends Schema
{
    public function parser(): Parser
    {
        if ($this->parser) {
            return $this->parser;
        }

        return $this->parser = new ProxyParser($this->newProxy());
    }

    /**
     * {@inheritDoc}
     */
    public function newInstance(): Model
    {
        return $this->newProxy()->toBase();
    }

    /**
     * {@inheritDoc}
     */
    public function isModel($model): bool
    {
        $expected = get_class($this->newInstance());

        return ($model instanceof $expected) || $expected === $model;
    }

    /**
     * Create a new proxy.
     */
    public function newProxy(?Model $model = null): ProxyContract
    {
        $proxyClass = $this->model();

        return new $proxyClass($model);
    }
}
