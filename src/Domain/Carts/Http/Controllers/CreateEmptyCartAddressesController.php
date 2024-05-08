<?php

namespace Dystcz\LunarApi\Domain\Carts\Http\Controllers;

use Dystcz\LunarApi\Base\Controller;
use Dystcz\LunarApi\Domain\Carts\Actions\CreateEmptyCartAddresses;
use Dystcz\LunarApi\Domain\Carts\Contracts\CreateEmptyCartAddressesController as CreateEmptyCartAddressesControllerContract;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartQuery;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CartSchema;
use Dystcz\LunarApi\Domain\Carts\JsonApi\V1\CreateEmptyCartAddressesRequest;
use Dystcz\LunarApi\Domain\Carts\Models\Cart;
use Illuminate\Support\Facades\App;
use LaravelJsonApi\Core\Responses\DataResponse;
use Lunar\Base\CartSessionInterface;
use Lunar\Managers\CartSessionManager;

class CreateEmptyCartAddressesController extends Controller implements CreateEmptyCartAddressesControllerContract
{
    /**
     * @var CartSessionManager
     */
    private CartSessionInterface $cartSession;

    public function __construct()
    {
        $this->cartSession = App::make(CartSessionInterface::class);
    }

    /**
     * Update an existing resource.
     *
     * @return \Illuminate\Contracts\Support\Responsable|\Illuminate\Http\Response
     */
    public function createEmptyAddresses(
        CartSchema $schema,
        CreateEmptyCartAddressesRequest $request,
        CartQuery $query,
        CreateEmptyCartAddresses $createEmptyCartAddresses
    ): DataResponse {
        /** @var Cart $cart */
        $cart = $this->cartSession->current();

        $this->authorize('createEmptyAddresses', $cart);

        $createEmptyCartAddresses->handle($cart);

        $model = $schema
            ->repository()
            ->queryOne($cart)
            ->withRequest($request)
            ->first();

        return DataResponse::make($model)
            ->withQueryParameters($query)
            ->didntCreate();
    }
}
