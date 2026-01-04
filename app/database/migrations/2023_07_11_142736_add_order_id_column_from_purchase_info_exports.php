<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderIdColumnFromPurchaseInfoExports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_info_exports', function (Blueprint $table) {
            $table->bigInteger('order_id')->nullable();
            $table->unique(['order_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_info_exports', function (Blueprint $table) {
            $table->dropUnique(['order_id']);
        });
    }
}
