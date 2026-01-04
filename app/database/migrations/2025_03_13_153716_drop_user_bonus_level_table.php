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
        Schema::dropIfExists('user_bonus_level');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('user_bonus_level')) {
            Schema::create('user_bonus_level', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('bonus_level_id')->constrained()->onDelete('cascade');
                $table->integer('current_bonus_points')->default(0);
                $table->timestamps();

                $table->unique('user_id');
            });
        }
    }
};
