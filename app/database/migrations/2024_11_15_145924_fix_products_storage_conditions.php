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
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'storage_conditions')) {
            Schema::table('products', function (Blueprint $table) {
                $table->text('storage_conditions')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'storage_conditions')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('storage_conditions')->nullable()->change();
            });
        }
    }
};
