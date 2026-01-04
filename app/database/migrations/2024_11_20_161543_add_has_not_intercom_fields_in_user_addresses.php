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
        if (Schema::hasTable('user_addresses') && !Schema::hasColumn('user_addresses', 'has_not_intercom')) {
            Schema::table('user_addresses', function (Blueprint $table) {
                $table->boolean('has_not_intercom')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('user_addresses', 'has_not_intercom')) {
            Schema::table('user_addresses', function (Blueprint $table) {
                $table->dropColumn('has_not_intercom');
            });
        }
    }
};
