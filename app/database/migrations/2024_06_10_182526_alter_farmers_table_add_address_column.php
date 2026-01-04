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
        if (Schema::hasTable('farmers') && !Schema::hasColumn('farmers', 'address')) {
            Schema::table('farmers', function (Blueprint $table) {
                $table->string('address')->after('description')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable('farmers') && Schema::hasColumn('farmers', 'address')) {
            Schema::table('farmers', function (Blueprint $table) {
                $table->dropColumn('address');
            });
        }
    }
};
