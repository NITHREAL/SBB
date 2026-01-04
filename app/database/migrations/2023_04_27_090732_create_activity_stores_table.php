<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_stores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->string('title')->nullable();
            $table->integer('number_sales')->nullable();
            $table->float('sum_sales', 22, 2)->nullable();
            $table->float('average_check', 22, 2)->nullable();
            $table->float('accrued_points', 22, 2)->nullable();
            $table->float('deducted_points', 22, 2)->nullable();
            $table->integer('amount_gifts')->nullable();
            $table->integer('new_users')->nullable();
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
        Schema::dropIfExists('activity_stores');
    }
}
