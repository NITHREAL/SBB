<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOptimizedIndexes extends Migration
{
    public function up()
    {
        Schema::table('category_product', function (Blueprint $table) {
            $table->index(['product_system_id', 'category_system_id']);
        });


        Schema::table('product_store', function (Blueprint $table) {
            $table->dropIndex(['product_system_id']);
            $table->dropIndex(['store_system_id']);
            $table->index(['product_system_id', 'store_system_id']);
        });

        Schema::table('product_delivery_dates', function (Blueprint $table) {
            $table->index(['product_id', 'date']);
        });
    }

    public function down()
    {
        Schema::table('category_product', function (Blueprint $table) {
            $table->dropIndex(['product_system_id', 'category_system_id']);
        });

        Schema::table('product_store', function (Blueprint $table) {
            $table->dropIndex(['product_system_id', 'store_system_id']);
            $table->index('product_system_id');
            $table->index( 'store_system_id');
        });

        Schema::table('product_delivery_dates', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'date']);
        });
    }
}
