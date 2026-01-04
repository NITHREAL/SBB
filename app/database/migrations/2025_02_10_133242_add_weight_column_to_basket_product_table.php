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
            Schema::hasTable('basket_product')
            && !Schema::hasColumn('basket_product', 'weight')
        ) {
            Schema::table('basket_product', function (Blueprint $table) {
                $table->unsignedFloat('weight')->default(0)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('basket_product')
            && Schema::hasColumn('basket_product', 'weight')
        ) {
            Schema::table('basket_product', function (Blueprint $table) {
                $table->dropColumn('weight');
            });
        }
    }
};
