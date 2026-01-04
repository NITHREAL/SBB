<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlinePaymentBindingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable('online_payment_bindings')) {
            Schema::create('online_payment_bindings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('acquiring_binding_id');
                $table->string('card_description');
                $table->string('expiry_date');
                $table->timestamps();

                $table->index(['user_id']);

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('online_payment_bindings');
    }
}
