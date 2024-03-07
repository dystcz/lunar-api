<?php

namespace Dystcz\LunarApi\Domain\OrderLines\Observers;

use Illuminate\Support\Facades\Config;
use Lunar\Base\Purchasable;
use Lunar\Exceptions\NonPurchasableItemException;
use Lunar\Models\OrderLine;

class OrderLineObserver
{
    protected array $nonEloquentTypes = [];

    public function __construct()
    {
        $this->nonEloquentTypes = Config::get('lunar-api.general.purchasable.non_eloquent_types', []);
    }

    /**
     * Handle the OrderLine "creating" event.
     */
    public function creating(OrderLine $orderLine): void
    {
        if (! in_array($orderLine->type, $this->nonEloquentTypes) && ! $orderLine->purchasable instanceof Purchasable) {
            throw new NonPurchasableItemException($orderLine->purchasable_type);
        }
    }

    /**
     * Handle the OrderLine "updating" event.
     */
    public function updating(OrderLine $orderLine): void
    {
        if (! in_array($orderLine->type, $this->nonEloquentTypes) && ! $orderLine->purchasable instanceof Purchasable) {
            throw new NonPurchasableItemException($orderLine->purchasable_type);
        }
    }
}
