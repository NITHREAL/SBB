<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddIndexesFromOtherTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            \DB::statement('create index email_logs_event_index on email_logs (event); ');
            \DB::statement('create index email_logs_mailable_id_mailable_type_index on email_logs (mailable_id, mailable_type);');
            \DB::statement('create index favorites_token_index on favorites (token);');
            \DB::statement('create index orders_sm_original_order_id_index on orders (sm_original_order_id);');
            \DB::statement('create index orders_payment_type_index on orders (payment_type);');
            \DB::statement('create index orders_status_index on orders (status)');
            \DB::statement('create index orders_store_system_id_index on orders (store_system_id);');
        } catch (\Exception $e) {}

        // Schema::table('orders', function (Blueprint $table) {
        //     $table->index('sm_original_order_id');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
