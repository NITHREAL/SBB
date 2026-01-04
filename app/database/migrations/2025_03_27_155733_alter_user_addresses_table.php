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
        if (
            Schema::hasTable('user_addresses')
            && !Schema::hasColumns('user_addresses', ['latitude', 'longitude'])
        ) {
            Schema::table('user_addresses', function (Blueprint $table) {
                $table->decimal('latitude', 17, 14)->nullable();
                $table->decimal('longitude', 17, 14)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('user_addresses')
            && Schema::hasColumns('user_addresses', ['latitude', 'longitude'])
        ) {
            Schema::table('user_addresses', function (Blueprint $table) {
                $table->dropColumn('latitude');
                $table->dropColumn('longitude');
            });
        }
    }
};
