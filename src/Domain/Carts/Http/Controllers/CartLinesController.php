<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use LaravelJsonApi\Laravel\Http\Controllers\Actions\Update;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Destroy;
use LaravelJsonApi\Laravel\Http\Controllers\Actions\Store;
use Dystcz\LunarApi\Controller;
use Dystcz\LunarApi\Domain\Carts\Actions\AddToCart;
use Dystcz\LunarApi\Domain\Carts\Data\CartLineData;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartLineRequest;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;

class CartLinesController extends Controller
{
    use Update;
    use Destroy;
    use Store;

    public function creating(CartLineRequest $request, $query)
    {
        $data = CartLineData::fromRequest($request);

        [, $cartLine] = App::make(AddToCart::class)($data);

        return DataResponse::make($cartLine)
            ->withQueryParameters($query)
            ->didCreate();
    }
}
