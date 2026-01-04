<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('system_id')->unique()->nullable();
            $table->string('store_system_id');
            $table->unsignedBigInteger('user_id');
            $table->string('payment_type');
            $table->string('delivery_type');
            $table->string('replacement_type')->nullable();
            $table->string('status')->default('created');
            $table->string('bill')->nullable();
            $table->boolean('contactless')->default(false);
            $table->text('comment')->nullable();
            $table->decimal('delivery_cost', 10)->unsigned()->nullable();
            $table->date('receive_date')->nullable();
            $table->string('receive_interval')->nullable();
            $table->boolean('need_exchange')->default(true);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
