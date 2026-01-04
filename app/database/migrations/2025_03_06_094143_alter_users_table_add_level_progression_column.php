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
            Schema::hasTable('users')
            && !Schema::hasColumn('users', 'loyalty_level_progression')
        ) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('loyalty_level_progression', 10, 2)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('users')
            && Schema::hasColumn('users', 'loyalty_level_progression')
        ) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('loyalty_level_progression');
            });
        }
    }
};
