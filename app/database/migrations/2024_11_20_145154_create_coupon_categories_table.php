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
        if (!Schema::hasTable('coupon_categories') && Schema::hasTable('attachments')) {
            Schema::create('coupon_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('image_id')->nullable();
                $table->string('system_id');
                $table->string('title');
                $table->text('description')->nullable();
                $table->text('purchase_terms')->nullable();
                $table->unsignedInteger('price');
                $table->unsignedInteger('sort');
                $table->boolean('active')->default(false);
                $table->timestamps();

                $table->index(['active']);

                $table->foreign('image_id')
                    ->references('id')
                    ->on('attachments')
                    ->onDelete('set null');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_categories');
    }
};
