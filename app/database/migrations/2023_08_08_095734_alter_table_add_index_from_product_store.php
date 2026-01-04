<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAddIndexFromProductStore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table('product_store', function (Blueprint $table) {
                $table->index('store_system_id');
                $table->index('product_system_id');
            });
        } catch (\Exception $e) {}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_store', function (Blueprint $table) {
            $table->dropIndex(['store_system_id']);
            $table->dropIndex(['product_system_id']);
        });
    }
}
