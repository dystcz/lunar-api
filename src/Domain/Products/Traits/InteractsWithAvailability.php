<?php

namespace Dystcz\LunarApi\Domain\Products\Traits;

use Dystcz\LunarApi\Base\Traits\InteractsWithAvailability as BaseInteractsWithAvailability;
use Dystcz\LunarApi\Domain\Products\Enums\Availability;
use Dystcz\LunarApi\Domain\Products\Models\Product;
use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Lunar\Models\Contracts\ProductVariant as ProductVariantContract;

trait InteractsWithAvailability
{
    use BaseInteractsWithAvailability;

    /**
     * Determine when model is considered to be always purchasable.
     */
    public function isAlwaysPurchasable(): bool
    {
        return false;
    }

    /**
     * Determine when model is considered to be backorderable.
     */
    public function isBackorderable(): bool
    {
        /** @var Product $this */
        $availability = $this->getAttribute('variants_availability');

        if ($availability->contains(Availability::BACKORDER)) {
            return true;
        }

        return false;
    }

    /**
     * Determine when model is considered to be in stock.
     */
    public function isInStock(): bool
    {
        /** @var Product $this */
        $availability = $this->getAttribute('variants_availability');

        if ($availability->contains(Availability::IN_STOCK) || $availability->contains(Availability::ALWAYS)) {
            return true;
        }

        return false;
    }

    /**
     * Prepare model for availability evaluation.
     */
    public function prepareModelForAvailabilityEvaluation(): void
    {
        /** @var Product $this */
        $this->setAttribute(
            'variants_availability',
            $this->variants->map(function (ProductVariantContract $variant) {
                /** @var ProductVariant $variant */
                if (! $variant->relationLoaded('product')) {
                    $variant->setRelation('product', $this);
                }

                return $variant->getAvailability();
            }));
    }
}
