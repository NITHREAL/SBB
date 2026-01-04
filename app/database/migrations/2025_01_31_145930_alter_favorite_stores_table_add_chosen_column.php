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
        if (Schema::hasTable('favorite_stores') && !Schema::hasColumn('favorite_stores', 'chosen')) {
            Schema::table('favorite_stores', function (Blueprint $table) {
                $table->boolean('chosen')->default(false)->after('store_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('favorite_stores') && Schema::hasColumn('favorite_stores', 'chosen')) {
            Schema::table('favorite_stores', function (Blueprint $table) {
                $table->dropColumn('chosen');
            });
        }
    }
};
