<?php

namespace Dystcz\LunarApi\Domain\Products\OpenApi\Responses;

use Dystcz\LunarApi\Domain\Products\OpenApi\Schemas\ProductSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class ShowProductResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::ok()->description('Successful response')->content(
            MediaType::json()->schema(ProductSchema::ref())
        );
    }
}
