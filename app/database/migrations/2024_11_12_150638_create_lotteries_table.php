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
        if (!Schema::hasTable('lotteries')) {
            Schema::create('lotteries', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('image_id')->nullable();
                $table->unsignedInteger('mini_image_id')->nullable();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('slug');
                $table->string('active_from')->nullable();
                $table->string('active_to')->nullable();
                $table->unsignedInteger('sort');
                $table->boolean('active')->default(false);
                $table->timestamps();

                $table->index(['slug']);

                $table->foreign('image_id')
                    ->references('id')
                    ->on('attachments')
                    ->onDelete('set null');
                $table->foreign('mini_image_id')
                    ->references('id')
                    ->on('attachments')
                    ->onDelete('set null');
            });
        }

        if (
            Schema::hasTable('lotteries')
            && !Schema::hasTable('lottery_products')
        ) {
            Schema::create('lottery_products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lottery_id')->constrained('lotteries')->cascadeOnDelete();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->unsignedInteger('sort');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lottery_products');
        Schema::dropIfExists('lotteries');
    }
};
