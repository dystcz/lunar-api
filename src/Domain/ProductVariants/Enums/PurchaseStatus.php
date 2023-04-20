<?php

namespace Dystcz\LunarApi\Domain\ProductVariants\Enums;

use Dystcz\LunarApi\Domain\ProductVariants\Models\ProductVariant;
use Illuminate\Contracts\Support\Arrayable;

enum PurchaseStatus implements Arrayable
{
    case AVAILABLE;

    case OUT_OF_STOCK;

    case PREORDER;

    /**
     * Get purchase status from product variant.
     */
    public static function fromProductVariant(ProductVariant $productVariant): self
    {
        return match (true) {
            $productVariant->stock <= 0 && $productVariant->purchasable === 'in-stock' => self::OUT_OF_STOCK,
            $productVariant->backorder <= 0 && $productVariant->purchasable === 'backorder' => self::OUT_OF_STOCK,
            $productVariant->attr('eta') !== null => self::PREORDER,
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
            self::PREORDER => __('Preorder'),
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
            self::PREORDER => 'blue',
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
