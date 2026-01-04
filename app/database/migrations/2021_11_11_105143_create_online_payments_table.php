<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlinePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_payments', function (Blueprint $table) {
            $table->id();
            $table
                ->string('sber_order_id')
                ->nullable();

            $table->boolean('surcharge')->default(false);
            $table->boolean('payed')->default(false);
            $table->decimal('amount', 10, 2, true);
            $table->string('form_url')->nullable();
            $table->string('error_code')->default(0);
            $table->string('error_message')->nullable();
            $table->timestamp('expires_in')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('online_payments');
    }
}
