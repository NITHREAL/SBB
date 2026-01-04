<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('bonus_levels')) {
            Schema::create('bonus_levels', function (Blueprint $table) {
                $table->id();
                $table->integer('number');
                $table->string('title');
                $table->integer('min_bonus_points');
                $table->integer('max_bonus_points')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_levels');
    }
};
