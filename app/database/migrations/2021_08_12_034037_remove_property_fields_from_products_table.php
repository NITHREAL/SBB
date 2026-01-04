<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePropertyFieldsFromProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_novelty');
            $table->dropColumn('is_popular');
            $table->dropColumn('is_special_offer');
            $table->dropColumn('is_vegan');
            $table->dropColumn('is_recommended_in_post');
            $table->dropColumn('is_delivery_in_country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->addColumn('boolean', 'is_novelty')->default(false);
            $table->addColumn('boolean', 'is_popular')->default(false);
            $table->addColumn('boolean', 'is_special_offer')->default(false);
            $table->addColumn('boolean', 'is_vegan')->default(false);
            $table->addColumn('boolean', 'is_recommended_in_post')->default(false);
            $table->addColumn('boolean', 'is_delivery_in_country')->default(false);
        });
    }
}
