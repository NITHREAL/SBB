<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsDeliveryDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_delivery_dates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->date('date');
        });

        // TODO после появления товаров нужно добавить сидер/команду с этой логикой
//        $products = Product::all();
//        foreach ($products as $product) {
//            if (!$product->delivery_dates) {
//                continue;
//            }
//            foreach($product->delivery_dates as $deliveryDate){
//                $product->deliveryDates()->create([
//                    'date' => $deliveryDate
//                ]);
//            }
//        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_delivery_dates');
    }
}
