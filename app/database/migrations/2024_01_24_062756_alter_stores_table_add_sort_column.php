<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStoresTableAddSortColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('stores') && !Schema::hasColumn('stores', 'sort')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->unsignedInteger('sort')->default(500)->after('longitude');
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
        if (Schema::hasTable('stores') && Schema::hasColumn('stores', 'sort')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn('sort');
            });
        }
    }
}
