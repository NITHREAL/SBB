<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableAddMiddleNameColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'middle_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('middle_name')->nullable()->after('first_name');
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
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'middle_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('middle_name');
            });
        }
    }
}
