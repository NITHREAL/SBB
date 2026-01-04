<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('system_id');
            $table->string('parent_system_id')->nullable();
            $table->unsignedBigInteger('margin_left');
            $table->unsignedBigInteger('margin_right');
            $table->unsignedInteger('level')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->timestamps();

            $table->unique('system_id');
            $table->foreign('parent_system_id')
                ->references('system_id')
                ->on('categories')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
}
