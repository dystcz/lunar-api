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
        Schema::table(Config::get('lunar.database.table_prefix').'orders', function (Blueprint $table) {
            $table->string('payment_option')->nullable()->index()->after('shipping_total');
            $table->integer('payment_total')->default(0)->unsigned()->index()->after('payment_option');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(Config::get('lunar.database.table_prefix').'orders', function (Blueprint $table) {
            $table->dropColumn('payment_option');
            $table->dropColumn('payment_total');
        });
    }
};
