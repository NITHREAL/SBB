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
            Schema::hasTable('order_contacts')
            && !Schema::hasColumns('order_contacts', ['latitude', 'longitude'])
        ) {
            Schema::table('order_contacts', function (Blueprint $table) {
                $table->decimal('latitude', 17, 14)->nullable()->after('address');
                $table->decimal('longitude', 17, 14)->nullable()->after('latitude');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('order_contacts')
            && Schema::hasColumns('order_contacts', ['latitude', 'longitude'])
        ) {
            Schema::table('order_contacts', function (Blueprint $table) {
                $table->dropColumn('latitude');
                $table->dropColumn('longitude');
            });
        }
    }
};
