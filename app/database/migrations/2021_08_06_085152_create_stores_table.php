<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('system_id')->unique();
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('active')->default(true);
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('address')->nullable();
            $table->string('work_time')->nullable();
            $table->decimal('latitude', 17, 14)->nullable();
            $table->decimal('longitude', 17, 14)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
}
