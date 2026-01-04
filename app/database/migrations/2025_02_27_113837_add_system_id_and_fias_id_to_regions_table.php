<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('regions')) {
            Schema::table('regions', function (Blueprint $table) {
                if (!Schema::hasColumn('regions', 'system_id')) {
                    $table->string('system_id')
                        ->after('id')
                        ->comment('Уникальный идентификатор 1С')
                        ->index()
                        ->unique();
                }

                if (!Schema::hasColumn('regions', 'fias_id')) {
                    $table->string('fias_id')
                        ->after('system_id')
                        ->comment('Уникальный идентификатор ФИАС')
                        ->index()
                        ->unique();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('regions')) {
            Schema::table('regions', function (Blueprint $table) {
                if (Schema::hasColumn('regions', 'system_id')) {
                    $table->dropColumn('system_id');
                }

                if (Schema::hasColumn('regions', 'fias_id')) {
                    $table->dropColumn('fias_id');
                }
            });
        }
    }
};
