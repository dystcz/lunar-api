<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Orders
        Schema::table($this->prefix.'orders', function (Blueprint $table) {
            $table->json('payment_breakdown')->nullable()->after('shipping_total');
            $table->integer('payment_total')->default(0)->unsigned()->index()->after('payment_breakdown');
        });

        // Carts
        Schema::table($this->prefix.'carts', function (Blueprint $table) {
            $table->string('payment_option')->nullable()->index()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Orders
        Schema::table($this->prefix.'orders', function (Blueprint $table) {
            $table->dropColumn('payment_breakdown');
            $table->dropColumn('payment_total');
        });

        // Carts
        Schema::table($this->prefix.'carts', function (Blueprint $table) {
            $table->dropColumn('payment_option');
        });
    }
};
