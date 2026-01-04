<?php

use App\Models\Enums\OrderExchangeTypeEnum;
use App\Models\Enums\OrderStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_exchanges', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')
                ->unsigned()
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();
            $table->string('type');
            $table->string('status');
            $table->json('data');
            $table->timestamp('created_at')->nullable()->default(Carbon::now());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_exchanges');
    }
}
