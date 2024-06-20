<?php

namespace Dystcz\LunarApi\Tests\Stubs\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use LaravelJsonApi\Core\Exceptions\JsonApiException;
use LaravelJsonApi\Exceptions\ExceptionParser;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        JsonApiException::class,
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(
            ExceptionParser::make()->renderable()
        );
    }
}
