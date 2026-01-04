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
        Schema::create('online_payment_binding_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->noActionOnDelete();
            $table->foreignId('payment_id')->nullable();

            $table->unsignedDecimal('amount', 10, 2)->default(0.00);

            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_payment_binding_requests');
    }
};
