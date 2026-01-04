<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStoriesTableAddAudienceIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('stories') && !Schema::hasColumn('stories', 'audience_id')) {
            Schema::table('stories', function (Blueprint $table) {
                $table->unsignedBigInteger('audience_id')->nullable()->after('id');

                $table->index(['audience_id']);

                $table->foreign('audience_id')
                    ->references('id')
                    ->on('audiences')
                    ->onDelete('cascade');
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
        if (Schema::hasTable('stories') && Schema::hasColumn('stories', 'audience_id')) {
            Schema::table('stories', function (Blueprint $table) {
                $table->dropForeign('stories_audience_id_foreign');
                $table->dropIndex('stories_audience_id_index');
                $table->dropColumn('audience_id');
            });
        }
    }
}
