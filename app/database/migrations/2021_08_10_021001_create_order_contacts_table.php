<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_contacts', function (Blueprint $table) {
            $table->bigInteger('order_id')->unsigned()->unique();
            $table->string('phone', 10);
            $table->string('email')->nullable();
            $table->boolean('send_email')->default(false);
            $table->string('address')->nullable();
            $table->string('apartment')->nullable();
            $table->integer('floor')->unsigned()->nullable();
            $table->integer('entrance')->unsigned()->nullable();
            $table->boolean('has_elevator')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_contacts');
    }
}
