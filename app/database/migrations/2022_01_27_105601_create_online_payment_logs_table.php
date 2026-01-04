<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlinePaymentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_payment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('online_payment_id')
                ->references('id')
                ->on('online_payments')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('method');
            $table->string('error_code')->nullable();
            $table->string('error_message')->nullable();
            $table->json('request')->nullable();
            $table->json('response')->nullable();
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
        Schema::dropIfExists('online_payment_logs');
    }
}
