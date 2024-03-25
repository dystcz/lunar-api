<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Orders
        Schema::table(Config::get('lunar.database.table_prefix').'orders', function (Blueprint $table) {
            $table->integer('payment_total')->default(0)->unsigned()->index()->after('shipping_total');
        });

        // Carts
        Schema::table(Config::get('lunar.database.table_prefix').'carts', function (Blueprint $table) {
            $table->string('payment_option')->nullable()->index()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Orders
        Schema::table(Config::get('lunar.database.table_prefix').'orders', function (Blueprint $table) {
            $table->dropColumn('payment_total');
        });

        // Carts
        Schema::table(Config::get('lunar.database.table_prefix').'carts', function (Blueprint $table) {
            $table->dropColumn('payment_option');
        });
    }
};
