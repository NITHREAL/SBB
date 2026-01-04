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
        if (Schema::hasTable('promos') && !Schema::hasColumn('promos', 'site')) {
            Schema::table('promos', function (Blueprint $table) {
                $table->boolean('site')->after('free_delivery')->default(true);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasTable('promos') && Schema::hasColumn('promos', 'site')) {
            Schema::table('promos', function (Blueprint $table) {
                $table->dropColumn('site');
            });
        }
    }
};
