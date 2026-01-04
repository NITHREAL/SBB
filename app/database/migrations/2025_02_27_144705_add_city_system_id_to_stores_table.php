<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('stores') && !Schema::hasColumn('stores', 'city_system_id')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->string('city_system_id')
                    ->after('id')
                    ->comment('Уникальный идентификатор 1С');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('stores') && Schema::hasColumn('stores', 'city_system_id')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn('city_system_id');
            });
        }
    }
};
