<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Enums;

use Illuminate\Contracts\Support\Arrayable;
use Lunar\Models\ProductVariant;

enum PurchaseStatus implements Arrayable
{
    case AVAILABLE;

    case OUT_OF_STOCK;

    /**
     * Get purchase status from product variant.
     */
    public static function fromProductVariant(ProductVariant $productVariant): self
    {
        return match ($productVariant) {
            $productVariant->stock <= 0 && $productVariant->purchasable === 'in-stock' => self::OUT_OF_STOCK,
            $productVariant->backorder <= 0 && $productVariant->purchasable === 'backorder' => self::OUT_OF_STOCK,
            default => self::AVAILABLE,
        };
    }

    /**
     * Get enum label.
     */
    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => __('Available'),
            self::OUT_OF_STOCK => __('Out of stock'),
        };
    }

    /**
     * Cast to array.
     */
    public function toArray(): array
    {
        return [
            'name' => __('Purchase status'),
            'value' => $this->label(),
        ];
    }
}
