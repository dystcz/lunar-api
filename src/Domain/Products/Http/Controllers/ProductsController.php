<?php

namespace Dystcz\LunarApi\Domain\Products\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class ProductsController extends Controller
{
    /**
     * List products.
     *
     * Lists all products based on provided filters.
     *
     * @return JsonResponse
     */
    #[OpenApi\Operation]
    public function index(): JsonResponse
    {
        return new JsonResponse(['products' => []], 200);
    }
}
