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
        if (!Schema::hasTable('order_settings')) {
            Schema::create('order_settings', function (Blueprint $table) {
                $table->id();

                $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

                $table->string('unavailable_settings')->nullable();
                $table->string('weight_settings')->nullable();
                $table->boolean('order_for_other_person_settings')->default(false);
                $table->string('other_person_phone')->nullable();
                $table->string('other_person_name')->nullable();
                $table->string('check_type')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_setting');
    }
};
