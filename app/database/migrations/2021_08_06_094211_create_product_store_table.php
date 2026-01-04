<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_store', function (Blueprint $table) {
            $table->string('hash');
            $table->string('store_system_id');
            $table->string('product_system_id')
                ->references('system_id')
                ->on('products')
                ->cascadeOnDelete();
            $table->boolean('active')->default(true);
            $table->decimal('price', 10)->unsigned()->nullable();
            $table->decimal('price_discount', 10)->unsigned()->nullable();
            $table->decimal('count', 10, 3)->unsigned()->default(0);
            $table->json('delivery_schedule')->nullable();

            $table->primary('hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_store');
    }
}
