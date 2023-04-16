<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Enums;

use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Illuminate\Contracts\Support\Arrayable;

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
     * Determine if purchasable.
     */
    public function purchasable(): bool
    {
        return in_array($this, [self::AVAILABLE]);
    }

    /**
     * Cast to array.
     */
    public function toArray(): array
    {
        return [
            'name' => __('Purchase status'),
            'value' => $this->label(),
            'purchasable' => $this->purchasable(),
        ];
    }
}
