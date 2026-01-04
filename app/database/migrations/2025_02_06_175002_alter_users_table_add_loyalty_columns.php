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
        if (Schema::hasTable('users')) {
            if (!Schema::hasColumn('users', 'loyalty_session_id')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('loyalty_session_id')->nullable();
                });
            }

            if (!Schema::hasColumn('users', 'loyalty_id')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('loyalty_id')->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            if (Schema::hasColumn('users', 'loyalty_session_id')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropColumn('loyalty_session_id');
                });
            }

            if (Schema::hasColumn('users', 'loyalty_id')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropColumn('loyalty_id');
                });
            }
        }
    }
};
