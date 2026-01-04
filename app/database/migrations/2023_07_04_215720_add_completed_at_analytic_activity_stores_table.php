<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompletedAtAnalyticActivityStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analytic_activity_stores', function (Blueprint $table) {
            $table->date('date_activity')
                ->nullable()
                ->after('new_users');
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
            $table->dropColumn('date_activity');
        });
    }
}
