<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStoriesTableAddAutoOpenColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('stories') && !Schema::hasColumn('stories', 'auto_open')) {
            Schema::table('stories', function (Blueprint $table) {
                $table->boolean('auto_open')->default(false)->after('active');
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
        if (Schema::hasTable('stories') && Schema::hasColumn('stories', 'auto_open')) {
            Schema::table('stories', function (Blueprint $table) {
                $table->dropColumn('auto_open');
            });
        }
    }
}
