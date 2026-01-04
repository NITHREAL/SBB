<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountExpiresInProductStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_store', function (Blueprint $table) {
            $table->timestamp('discount_expires_in')
                ->nullable()
                ->after('price_discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_store', function (Blueprint $table) {
            $table->dropColumn('price_discount');
        });
    }
}
