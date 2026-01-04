<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->boolean('only_one_use')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->removeColumn('only_one_use');
        });
    }
};
