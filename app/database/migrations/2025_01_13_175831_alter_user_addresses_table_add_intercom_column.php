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
        if (Schema::hasTable('user_addresses') && !Schema::hasColumn('user_addresses', 'intercom')) {
            Schema::table('user_addresses', function (Blueprint $table) {
                $table->string('intercom')->nullable()->after('entrance');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('user_addresses') && Schema::hasColumn('user_addresses', 'intercom')) {
            Schema::table('user_addresses', function (Blueprint $table) {
                $table->dropColumn('intercom');
            });
        }
    }
};
