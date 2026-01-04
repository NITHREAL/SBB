<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountInOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->decimal('price_discount', 10, 2, true)
                ->nullable()
                ->after('price');

            $table->boolean('is_discount')
                ->default(false)
                ->after('price_buy');

            $table->decimal('weight', 10, 3)
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('price_discount');
            $table->dropColumn('is_discount');
            $table->dropColumn('weight');
        });
    }
}
