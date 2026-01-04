<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGroupProductsAddStoriesColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('groups')) {
            if (!Schema::hasColumn('groups', 'story_id')) {
                Schema::table('groups', function (Blueprint $table) {
                    $table->unsignedBigInteger('story_id')->nullable()->after('audience_id');

                    $table->foreign('story_id')
                        ->references('id')
                        ->on('stories');
                });
            }

            if (!Schema::hasColumn('groups', 'background_image_id')) {
                Schema::table('groups', function (Blueprint $table) {
                    $table->unsignedInteger('background_image_id')->nullable()->after('story_id');

                    $table->foreign('background_image_id')
                        ->references('id')
                        ->on('attachments');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable('groups')) {
            if (Schema::hasColumn('groups', 'story_id')) {
                Schema::table('groups', function (Blueprint $table) {
                    $table->dropColumn('story_id');
                });
            }

            if (Schema::hasColumn('groups', 'background_image_id')) {
                Schema::table('groups', function (Blueprint $table) {
                    $table->dropColumn('background_image_id');
                });
            }
        }
    }
}
