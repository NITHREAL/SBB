<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOnlinePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_payments', function (Blueprint $table) {
            $table->dropColumn('surcharge');
            $table->dropColumn('cash_balance');

            $table->string('status')
                ->after('sber_order_id')
                ->nullable();

            $table->decimal('value', 10)
                ->after('amount')
                ->nullable();
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
            $table->boolean('surcharge')->default(false);
            $table->decimal('cash_balance', 10)
                ->unsigned()
                ->after('amount');

            $table->dropColumn('status');
            $table->dropColumn('value');
        });
    }
}
