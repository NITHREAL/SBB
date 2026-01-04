<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignToStoresAnalyticActivityStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analytic_activity_stores', function (Blueprint $table) {
            $table->foreign('store_id')
                ->references('id')
                ->on('stores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analytic_activity_stores', function (Blueprint $table) {
            $table->dropForeign('analytic_activity_stores_store_id_foreign');
        });
    }
}
