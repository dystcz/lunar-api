<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Enums;

use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Illuminate\Contracts\Support\Arrayable;

enum PurchaseStatus implements Arrayable
{
    case PREORDER;

    case OUT_OF_STOCK;

    case AVAILABLE;

    /**
     * Get purchase status from product variant.
     */
    public static function fromProductVariant(ProductVariant $productVariant): self
    {
        return match (true) {
            $productVariant->attr('eta') !== null => self::PREORDER,
            $productVariant->stock <= 0 && $productVariant->purchasable === 'in_stock' => self::OUT_OF_STOCK,
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
            self::PREORDER => __('Preorder'),
            self::OUT_OF_STOCK => __('Out of stock'),
            self::AVAILABLE => __('Available'),
        };
    }

    /**
     * Get enum color.
     */
    public function color(): string
    {
        return match ($this) {
            self::PREORDER => 'blue',
            self::OUT_OF_STOCK => 'grey',
            self::AVAILABLE => 'green',
        };
    }

    /**
     * Determine if purchasable.
     */
    public function purchasable(): bool
    {
        return in_array($this, [self::AVAILABLE, self::PREORDER]);
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
