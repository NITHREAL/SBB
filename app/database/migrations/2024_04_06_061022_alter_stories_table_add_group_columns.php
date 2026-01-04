<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStoriesTableAddGroupColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('stories') && !Schema::hasColumn('stories', 'available_in_groups')) {
            Schema::table('stories', function (Blueprint $table) {
                $table->boolean('available_in_groups')->default(false)->after('auto_open');
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
        if (Schema::hasTable('stories') && Schema::hasColumn('stories', 'available_in_groups')) {
            Schema::table('stories', function (Blueprint $table) {
                $table->dropColumn('available_in_groups');
            });
        }
    }
}
