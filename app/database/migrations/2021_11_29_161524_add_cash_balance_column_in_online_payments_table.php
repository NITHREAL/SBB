<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCashBalanceColumnInOnlinePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_payments', function (Blueprint $table) {
            $table->decimal('cash_balance', 10)
                ->unsigned()
                ->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_payments', function (Blueprint $table) {
            $table->dropColumn('cash_balance');
        });
    }
}
