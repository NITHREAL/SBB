<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('system_id')->unique();
            $table->string('farmer_system_id');
            $table->string('unit_system_id');
            $table->boolean('active')->default(true);
            $table->string('sku')->unique()->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->longText('composition')->nullable();
            $table->string('storage_conditions')->nullable();
            $table->decimal('proteins', 8, 1)->unsigned()->nullable();
            $table->decimal('fats', 8, 1)->unsigned()->nullable();
            $table->decimal('carbohydrates', 8, 1)->unsigned()->nullable();
            $table->decimal('nutrition_kcal', 10, 1)->unsigned()->nullable();
            $table->decimal('nutrition_kj', 10, 1)->unsigned()->nullable();
            $table->decimal('weight')->unsigned()->nullable();
            $table->decimal('rating', 4)->unsigned()->nullable();
            $table->integer('shelf_life')->unsigned()->nullable();
            $table->boolean('is_novelty')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_special_offer')->default(false);
            $table->boolean('is_vegan')->default(false);
            $table->boolean('is_recommended_in_post')->default(false);
            $table->boolean('is_perishable')->default(false);
            $table->boolean('is_delivery_in_country')->default(false);
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
        Schema::dropIfExists('products');
    }
}
