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
        if (Schema::hasTable('story_pages') && !Schema::hasColumn('story_pages', 'label_color')) {
            Schema::table('story_pages', function (Blueprint $table) {
                $table->string('label_color')->nullable()->after('label');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('story_pages') && Schema::hasColumn('story_pages', 'label_color')) {
            Schema::table('story_pages', function (Blueprint $table) {
                $table->dropColumn('label_color');
            });
        }
    }
};
