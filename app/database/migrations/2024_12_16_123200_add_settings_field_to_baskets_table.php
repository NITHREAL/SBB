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
        if(!Schema::hasColumn('baskets', 'settings')){
            Schema::table('baskets', function (Blueprint $table) {
                $table->json('settings')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('baskets') && Schema::hasColumn('baskets', 'settings')) {
            Schema::table('baskets', function (Blueprint $table) {
                $table->dropColumn('settings');
            });
        }
    }
};
