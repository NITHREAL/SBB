<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class AlterStoriesTableChangeImageColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('stories') && !Schema::hasColumn('stories', 'image_id')) {
            Schema::table('stories', function (Blueprint $table) {
                $table->unsignedInteger('image_id')->nullable()->after('audience_id');

                $table->foreign('image_id')
                    ->references('id')
                    ->on('attachments');
            });
        }

        if (
            Schema::hasTable('stories') && Schema::hasColumn('stories', 'image_id')
            && Schema::hasColumn('stories', 'image')
        ) {
            Schema::table('stories', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('stories') && Schema::hasColumn('stories', 'image_id')) {
            Schema::table('stories', function (Blueprint $table) {
                $table->dropColumn('image_id');
            });
        }

        if (
            Schema::hasTable('stories') && !Schema::hasColumn('stories', 'image')
        ) {
            Schema::table('stories', function (Blueprint $table) {
                $table->string('image')->nullable()->after('audience_id');
            });
        }
    }
}
