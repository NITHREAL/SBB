<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('group_products')) {
            Schema::create('group_products', function (Blueprint $table) {
                $table->unsignedBigInteger('group_id');
                $table->unsignedBigInteger('product_id');
                $table->unsignedInteger('sort')->default(0);

                $table->index(['group_id']);
                $table->index(['product_id']);

                $table->foreign('group_id')
                    ->references('id')
                    ->on('groups')
                    ->cascadeOnDelete();
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->cascadeOnDelete();
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
        Schema::dropIfExists('group_products');
    }
}
