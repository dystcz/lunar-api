<?php

namespace Dystcz\LunarApi\Domain\Orders\Models;

use Dystcz\LunarApi\Domain\Orders\Concerns\InteractsWithLunarApi;
use Dystcz\LunarApi\Domain\Orders\Contracts\Order as OrderContract;
use Lunar\Models\Order as LunarOrder;

/**
 * @method Illuminate\Database\Eloquent\Builder productLines()
 * @method Illuminate\Database\Eloquent\Builder paymentLines()
 * @method Illuminate\Database\Eloquent\Builder latestTransaction()
 *
 * @property int $payment_total
 * @property array $payment_breakdown
 * @property Illuminate\Database\Eloquent\Collection $product_lines
 * @property Illuminate\Database\Eloquent\Collection $productLines
 * @property Illuminate\Database\Eloquent\Collection $payment_lines
 * @property Illuminate\Database\Eloquent\Collection $paymentLines
 * @property Illuminate\Database\Eloquent\Collection $latest_transaction
 * @property Illuminate\Database\Eloquent\Collection $latestTransaction
 */
class Order extends LunarOrder implements OrderContract
{
    use InteractsWithLunarApi;
}
