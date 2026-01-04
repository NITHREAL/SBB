<?php

use App\Models\Enums\OrderProductStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->unsigned()->index();
            $table->string('product_system_id')->index();
            $table->string('replacement_system_id')->nullable();
            $table->string('unit_system_id');
            $table->string('status')->nullable();
            $table->decimal('price', 10)->unsigned();
            $table->decimal('price_buy', 10)->unsigned()->nullable();
            $table->decimal('count', 10, 3, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product');
    }
}
