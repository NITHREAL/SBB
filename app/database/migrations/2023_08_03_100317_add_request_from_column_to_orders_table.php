<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRequestFromColumnToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table
                ->string('request_from')
                ->nullable()
                ->default('site');

            $table->index(['request_from']);
        });


        DB::table('orders')
            ->where('is_offline', true)
            ->update([
                'request_from' => 'offline'
            ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['request_from']);
            $table->dropColumn(['request_from']);
        });
    }
}
