<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelatedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable('related_products')) {
            Schema::create('related_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('main_product_id');
                $table->unsignedBigInteger('related_product_id');
                $table->unsignedInteger('sort');

                $table->index(['main_product_id']);

                $table->foreign('main_product_id')->references('id')->on('products')->onDelete('cascade');
                $table->foreign('related_product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('related_products');
    }
}
