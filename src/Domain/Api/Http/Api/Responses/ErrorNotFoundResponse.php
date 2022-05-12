<?php

namespace Dystcz\GetcandyApi\Domain\Api\Http\Api\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class ErrorNotFoundResponse extends ResponseFactory
{
    public function build(): Response
    {
        $response = Schema::object()->properties(
            Schema::string('message')->example('No query results for model ...'),
        );

        return Response::create('Model not found')
            ->description('Errors')
            ->content(
                MediaType::json()->schema($response)
            );
    }
}
