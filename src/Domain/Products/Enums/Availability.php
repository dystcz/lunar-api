<?php

namespace Dystcz\LunarApi\Domain\Products\Enums;

use Dystcz\LunarApi\Base\Contracts\HasAvailability;
use Illuminate\Contracts\Support\Arrayable;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum Availability: string implements Arrayable
{
    case ALWAYS = 'always';
    case IN_STOCK = 'in_stock';
    case OUT_OF_STOCK = 'out_of_stock';
    case BACKORDER = 'backorder';
    case PREORDER = 'preorder';

    /**
     * Get purchase status of a model.
     */
    public static function of(HasAvailability $model): self
    {
        $model->prepareModelForAvailabilityEvaluation();

        return match (true) {
            $model->isPreorderable() => self::PREORDER,
            $model->isAlwaysPurchasable() => self::ALWAYS,
            $model->isInStock() => self::IN_STOCK,
            $model->isBackorderable() => self::BACKORDER,
            default => self::OUT_OF_STOCK,
        };
    }

    /**
     * Get enum label.
     */
    public function label(): string
    {
        return match ($this) {
            self::ALWAYS => __('lunar-api::availability.labels.always'),
            self::IN_STOCK => __('lunar-api::availability.labels.in_stock'),
            self::BACKORDER => __('lunar-api::availability.labels.backorder'),
            self::PREORDER => __('lunar-api::availability.labels.preorder'),
            self::OUT_OF_STOCK => __('lunar-api::availability.labels.out_of_stock'),
        };
    }

    /**
     * Get schema.org value.
     */
    public function schemaOrg(): string
    {
        return match ($this) {
            self::ALWAYS => 'InStock',
            self::IN_STOCK => 'InStock',
            self::BACKORDER => 'BackOrder',
            self::PREORDER => 'PreOrder',
            self::OUT_OF_STOCK => 'OutOfStock',
        };
    }

    /**
     * Get enum color.
     */
    public function color(): string
    {
        return match ($this) {
            self::ALWAYS => 'green',
            self::IN_STOCK => 'green',
            self::BACKORDER => 'blue',
            self::PREORDER => 'blue',
            self::OUT_OF_STOCK => 'red',
        };
    }

    /**
     * Determine if purchasable.
     */
    public function purchasable(): bool
    {
        return in_array($this, [
            self::ALWAYS,
            self::IN_STOCK,
            self::BACKORDER,
            self::PREORDER,
        ]);
    }

    /**
     * Cast to array.
     *
     * @return array<string,string>
     */
    public function toArray(): array
    {
        return [
            'name' => __('lunar-api::availability.availability'),
            'value' => $this->value,
            'label' => $this->label(),
            'color' => $this->color(),
            'schema_org' => $this->schemaOrg(),
            'purchasable' => $this->purchasable(),
        ];
    }
}
