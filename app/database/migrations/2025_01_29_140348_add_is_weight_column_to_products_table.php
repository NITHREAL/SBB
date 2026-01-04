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
            Schema::hasTable('products')
            && !Schema::hasColumn('products', 'is_weight')
        ) {
            Schema::table('products', function (Blueprint $table) {
               $table->boolean('is_weight')->default(false)->after('nutrition_kj');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('products')
            && Schema::hasColumn('products', 'is_weight')
        ) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('is_weight');
            });
        }
    }
};
