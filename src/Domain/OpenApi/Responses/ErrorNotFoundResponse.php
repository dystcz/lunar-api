<?php

namespace Dystcz\LunarApi\Domain\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class ErrorNotFoundResponse extends ResponseFactory
{
    public function build(): Response
    {
        $schema = Schema::object()->properties(
            Schema::string('message')->example('No query results for model ...'),
        );

        return Response::notFound()
            ->description('Errors')
            ->content(
                MediaType::json()->schema($schema)
            );
    }
}
