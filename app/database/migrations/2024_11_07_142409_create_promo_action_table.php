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
        if (!Schema::hasTable('promo_actions')) {
            Schema::create('promo_actions', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('image_id')->nullable();
                $table->unsignedInteger('mini_image_id')->nullable();
                $table->string('title');
                $table->text('description')->nullable();
                $table->text('short_description')->nullable();
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
            Schema::hasTable('promo_actions')
            && !Schema::hasTable('promo_action_products')
        ) {
            Schema::create('promo_action_products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('promo_action_id')->constrained('promo_actions')->cascadeOnDelete();
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
        Schema::dropIfExists('promo_action_products');
        Schema::dropIfExists('promo_actions');
    }
};
