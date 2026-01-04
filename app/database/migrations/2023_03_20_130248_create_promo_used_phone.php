<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoUsedPhone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_used_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_id')
                ->nullable()
                ->references('id')
                ->on('promos')
                ->nullOnDelete();
            $table->string('phone', 10)
                ->nullable()
                ->default(null);
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
        Schema::dropIfExists('promo_used_phones');
    }
}
