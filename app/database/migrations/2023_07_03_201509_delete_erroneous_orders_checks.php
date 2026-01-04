<?php

use App\Models\ExternalCheck;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteErroneousOrdersChecks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // TODO добавить после появления чеков
//        ExternalCheck::query()
//            ->whereNull('order_id')
//            ->forceDelete();
//
//        $orders = Order::query()
//            ->whereNull('user_id')
//            ->orderBy('id', 'desc')
//            ->get();
//
//        foreach ($orders as $order) {
//            $orderProducts = OrderProduct::query()
//                ->where('order_id', $order->id)
//                ->get();
//
//            foreach ($orderProducts as $orderProduct) {
//                $orderProduct->forceDelete();
//            }
//
//            $order->forceDelete();
//        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
