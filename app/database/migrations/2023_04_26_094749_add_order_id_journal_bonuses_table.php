<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderIdJournalBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('journal_bonuses', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('store_id')->comment('Заказ, ссылка на orders');

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
        Schema::table('journal_bonuses', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
    }
}
