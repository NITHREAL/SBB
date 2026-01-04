<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeleteIsOfflineColumnFromOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['is_offline']);
            $table->dropColumn(['is_offline']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_offline')->default(false);
            $table->index(['is_offline']);
        });

        DB::table('orders')
            ->where('request_from', 'offline')
            ->update([
                'is_offline' => true
            ]);
    }
}
