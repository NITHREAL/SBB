<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryPricesInPolygonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_prices_in_polygons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('polygon_id')->constrained()->cascadeOnDelete();
            $table->decimal('from', 10)->unsigned()->nullable();
            $table->decimal('to', 10)->unsigned()->nullable();
            $table->decimal('price', 10)->unsigned()->nullable();
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
        Schema::dropIfExists('delivery_prices_in_polygons');
    }
}
