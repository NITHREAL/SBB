<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePaymentStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('payment_store', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')
                ->references('id')
                ->on('payments')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('store_id')
                ->references('id')
                ->on('stores')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_store');
    }
}
