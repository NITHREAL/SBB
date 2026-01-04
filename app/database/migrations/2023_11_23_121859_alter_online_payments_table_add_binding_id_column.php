<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOnlinePaymentsTableAddBindingIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (
            Schema::hasTable('online_payments')
            && !Schema::hasColumn('online_payments', 'binding_id')
        ) {
            Schema::table('online_payments', function (Blueprint $table) {
                $table->unsignedBigInteger('binding_id')->nullable()->after('sber_order_id');

                $table->foreign('binding_id')
                    ->references('id')
                    ->on('online_payment_bindings');
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
        if (
            Schema::hasTable('online_payments')
            && Schema::hasColumn('online_payments', 'binding_id')
        ) {
            Schema::table('online_payments', function (Blueprint $table) {
                $table->dropForeign('online_payments_binding_id_foreign');
                $table->dropColumn('binding_id');
            });
        }
    }
}
