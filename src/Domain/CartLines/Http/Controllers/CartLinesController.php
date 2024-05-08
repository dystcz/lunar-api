<?php

namespace Dystcz\LunarApi\Domain\CartLines\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\CartLines\Actions\UpdateCartLine;
use Dystcz\LunarApi\Domain\CartLines\Contracts\CartLinesController as CartLinesControllerContract;
use Dystcz\LunarApi\Domain\CartLines\Data\CartLineData;
use Dystcz\LunarApi\Domain\CartLines\JsonApi\V1\CartLineQuery;
use Dystcz\LunarApi\Domain\CartLines\JsonApi\V1\CartLineRequest;
use Dystcz\LunarApi\Domain\CartLines\Models\CartLine;
use Dystcz\LunarApi\Domain\Carts\Actions\AddToCart;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Destroy;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Store;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Update;

class CartLinesController extends Controller implements CartLinesControllerContract
{
    use Destroy;
    use Store;
    use Update;

    /*
     * Add cart line to cart.
     */
    public function creating(CartLineRequest $request, CartLineQuery $query): DataResponse
    {
        $data = CartLineData::fromRequest($request);

        [, $cartLine] = App::make(AddToCart::class)($data);

        return DataResponse::make($cartLine)
            ->withQueryParameters($query)
            ->didCreate();
    }

    /*
     * Update cart line.
     */
    public function updating(CartLine $cartLine, CartLineRequest $request, CartLineQuery $query): DataResponse
    {
        $data = CartLineData::fromRequest($request);

        [, $cartLine] = App::make(UpdateCartLine::class)($data, $cartLine);

        return DataResponse::make($cartLine)
            ->withQueryParameters($query)
            ->didntCreate();
    }
}
