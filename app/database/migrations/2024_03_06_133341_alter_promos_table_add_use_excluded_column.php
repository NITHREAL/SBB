<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPromosTableAddUseExcludedColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('promos') && !Schema::hasColumn('promos', 'use_excluded')) {
            Schema::table('promos', function (Blueprint $table) {
                $table->boolean('use_excluded')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable('promos') && Schema::hasColumn('promos', 'use_excluded')) {
            Schema::table('promos', function (Blueprint $table) {
                $table->dropColumn('use_excluded');
            });
        }
    }
}
