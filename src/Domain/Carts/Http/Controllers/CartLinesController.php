<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Carts\Actions\AddToCart;
use Dystcz\LunarApi\Domain\Carts\Actions\UpdateCartLine;
use Dystcz\LunarApi\Domain\Carts\Data\CartLineData;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartLineRequest;
use Dystcz\LunarApi\Domain\Carts\Models\CartLine;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Destroy;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Store;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Update;

class CartLinesController extends Controller
{
    use Update;
    use Destroy;
    use Store;

    public function creating(CartLineRequest $request, $query): DataResponse
    {
        $data = CartLineData::fromRequest($request);

        [, $cartLine] = App::make(AddToCart::class)($data);

        return DataResponse::make($cartLine)
            ->withQueryParameters($query)
            ->didCreate();
    }

    public function updating(CartLine $cartLine, CartLineRequest $request, $query): DataResponse
    {
        $data = CartLineData::fromRequest($request);

        [, $cartLine] = App::make(UpdateCartLine::class)($data, $cartLine);

        return DataResponse::make($cartLine)
            ->withQueryParameters($query)
            ->didntCreate();
    }
}
