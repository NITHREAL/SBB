<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameForeignExternalChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_checks', function (Blueprint $table) {
            $table->dropForeign('journal_bonuses_order_id_foreign');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_checks', function (Blueprint $table) {
            $table->dropForeign('external_checks_order_id_foreign');
            $table->foreign('order_id', 'journal_bonuses_order_id_foreign')
                ->references('id')
                ->on('orders');
        });
    }
}
