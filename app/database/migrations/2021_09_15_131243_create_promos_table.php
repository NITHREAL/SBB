<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(true);
            $table->string('code');
            $table->integer('discount')->unsigned();
            $table->boolean('percentage')->default(false);
            $table->unsignedInteger('limit')->nullable();
            $table->decimal('min_amount', 10, 2, true)->nullable();
            $table->string('order_type')->default('any');
            $table->boolean('any_product')->default(false);
            $table->boolean('any_user')->default(false);
            $table->timestamp('expires_in')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promos');
    }
}
