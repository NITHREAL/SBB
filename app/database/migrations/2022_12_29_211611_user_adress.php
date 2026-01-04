<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserAdress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('user_addresses')) {
            Schema::create('user_addresses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('city_id');
                $table->string('address');
                $table->string('city_name')->nullable();
                $table->string('street')->nullable();
                $table->string('house')->nullable();
                $table->string('building')->nullable();
                $table->string('entrance')->nullable();
                $table->string('apartment')->nullable();
                $table->string('floor')->nullable();
                $table->tinyText('comment')->nullable();
                $table->boolean('other_customer')->default(false);
                $table->string('other_customer_phone')->nullable();
                $table->string('other_customer_name')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'city_id']);

                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                $table->foreign('city_id')->references('id')->on('cities');
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
        Schema::dropIfExists('user_addresses');
    }
}
