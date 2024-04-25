<?php

namespace Dystcz\LunarApi\Domain\Products\Enums;

use Illuminate\Contracts\Support\Arrayable;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;

enum Availability: string implements Arrayable
{
    case IN_STOCK = 'in_stock';
    case OUT_OF_STOCK = 'out_of_stock';
    case BACKORDER = 'backorder';
    case PREORDER = 'preorder';

    /**
     * Get purchase status of a model.
     */
    public static function of(Product|ProductVariant $model): self
    {
        if ($model instanceof ProductVariant) {
            return self::fromProductVariant($model);
        }

        return self::fromProduct($model);
    }

    /**
     * Get purchase status from product.
     */
    public static function fromProduct(Product $product): self
    {
        $variantsStatuses = $product->variants->map(
            fn (ProductVariant $variant) => self::fromProductVariant($variant),
        );

        if ($product->attr('eta') || $variantsStatuses->contains(self::PREORDER)) {
            return self::PREORDER;
        }

        if ($variantsStatuses->contains(self::BACKORDER)) {
            return self::BACKORDER;
        }

        if ($variantsStatuses->contains(self::IN_STOCK)) {
            return self::IN_STOCK;
        }

        return self::OUT_OF_STOCK;
    }

    /**
     * Get purchase status from product variant.
     */
    public static function fromProductVariant(ProductVariant $productVariant): self
    {
        return match (true) {
            $productVariant->attr('eta') !== null && $productVariant->attr('eta') !== '' => self::PREORDER,
            $productVariant->stock >= 0 && $productVariant->purchasable === 'in_stock' => self::IN_STOCK,
            $productVariant->backorder >= 0 && $productVariant->purchasable === 'backorder' => self::BACKORDER,
            default => self::OUT_OF_STOCK,
        };
    }

    /**
     * Get enum label.
     */
    public function label(): string
    {
        return match ($this) {
            self::IN_STOCK => __('In Stock'),
            self::OUT_OF_STOCK => __('Out of stock'),
            self::BACKORDER => __('Backorder'),
            self::PREORDER => __('Preorder'),
        };
    }

    /**
     * Get schema.org value.
     */
    public function schemaOrg(): string
    {
        return match ($this) {
            self::IN_STOCK => 'InStock',
            self::OUT_OF_STOCK => 'OutOfStock',
            self::BACKORDER => 'BackOrder',
            self::PREORDER => 'PreOrder',
        };
    }

    /**
     * Get enum color.
     */
    public function color(): string
    {
        return match ($this) {
            self::IN_STOCK => 'green',
            self::OUT_OF_STOCK => 'red',
            self::BACKORDER => 'blue',
            self::PREORDER => 'blue',
        };
    }

    /**
     * Determine if purchasable.
     */
    public function purchasable(): bool
    {
        return in_array($this, [self::IN_STOCK, self::BACKORDER, self::PREORDER]);
    }

    /**
     * Cast to array.
     *
     * @return array<string,string>
     */
    public function toArray(): array
    {
        return [
            'name' => __('Availability'),
            'value' => $this->value,
            'label' => $this->label(),
            'color' => $this->color(),
            'schema_org' => $this->schemaOrg(),
            'purchasable' => $this->purchasable(),
        ];
    }
}
