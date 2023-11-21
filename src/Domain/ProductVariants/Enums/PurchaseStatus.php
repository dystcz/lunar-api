<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Enums;

use Illuminate\Contracts\Support\Arrayable;
use Lunar\Models\ProductVariant;

enum PurchaseStatus implements Arrayable
{
    case AVAILABLE;

    case OUT_OF_STOCK;

    case BACKORDER;

    /**
     * Get purchase status from product variant.
     */
    public static function fromProductVariant(ProductVariant $productVariant): self
    {
        return match (true) {
            $productVariant->purchasable === 'always' || ($productVariant->stock > 0 && $productVariant->purchasable === 'in_stock') => self::AVAILABLE,
            $productVariant->backorder > 0 && $productVariant->purchasable === 'backorder' => self::BACKORDER,
            default => self::OUT_OF_STOCK,
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
            self::BACKORDER => __('Preorder'),
        };
    }

    /**
     * Get enum color.
     */
    public function color(): string
    {
        return match ($this) {
            self::AVAILABLE => 'green',
            self::OUT_OF_STOCK => 'grey',
            self::BACKORDER => 'blue',
        };
    }

    /**
     * Determine if purchasable.
     */
    public function purchasable(): bool
    {
        return in_array($this, [self::AVAILABLE, self::BACKORDER]);
    }

    /**
     * Cast to array.
     */
    public function toArray(): array
    {
        return [
            'name' => __('Purchase status'),
            'value' => $this->label(),
            'color' => $this->color(),
            'purchasable' => $this->purchasable(),
        ];
    }
}
