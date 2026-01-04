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
            Schema::hasTable('story_metadata')
            && Schema::hasTable('users')
            && !Schema::hasColumn('story_metadata', 'user_id')
        ) {
            Schema::table('story_metadata', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('story_id');

                $table->index(['user_id']);

                $table->foreign('user_id')->references('id')->on('users');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('story_metadata')
            && Schema::hasTable('users')
            && Schema::hasColumn('story_metadata', 'user_id')
        ) {
            Schema::table('story_metadata', function (Blueprint $table) {
                $table->dropForeign('story_metadata_user_id_foreign');
                $table->dropIndex('story_metadata_user_id_index');
                $table->dropColumn('user_id');
            });
        }
    }
};
