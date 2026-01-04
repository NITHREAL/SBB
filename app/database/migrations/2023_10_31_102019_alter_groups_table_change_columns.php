<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGroupsTableChangeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('groups')) {
            if (!Schema::hasColumn('groups', 'site')) {
                Schema::table('groups', function (Blueprint $table) {
                    $table->boolean('site')->after('sort')->default(false);
                });
            }

            if (!Schema::hasColumn('groups', 'audience_id')) {
                Schema::table('groups', function (Blueprint $table) {
                    $table->unsignedBigInteger('audience_id')->after('id')->nullable();

                    $table->index(['audience_id']);

                    $table->foreign('audience_id')
                        ->references('id')
                        ->on('audiences');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('groups')) {
            if (Schema::hasColumn('groups', 'site')) {
                Schema::table('groups', function (Blueprint $table) {
                    $table->dropColumn('site');
                });
            }

            if (Schema::hasColumn('groups', 'audience_id')) {
                Schema::table('groups', function (Blueprint $table) {
                    $table->dropForeign('groups_audience_id_foreign');
                    $table->dropIndex('groups_audience_id_index');
                    $table->dropColumn('audience_id');
                });
            }
        }
    }
}
