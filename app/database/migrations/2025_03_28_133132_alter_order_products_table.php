<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (
            Schema::hasTable('order_product')
            && !Schema::hasColumn('order_product', 'collected_quantity')
        ) {
            Schema::table('order_product', function (Blueprint $table) {
                $table->unsignedDecimal('collected_quantity', 10, 3)->nullable()->after('original_quantity');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('order_product')
            && Schema::hasColumn('order_product', 'collected_quantity')
        ) {
            Schema::table('order_product', function (Blueprint $table) {
                $table->dropColumn('collected_quantity');
            });
        }
    }
};
