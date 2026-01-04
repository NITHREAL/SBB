<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cities')) {
            Schema::table('cities', function (Blueprint $table) {
                if (Schema::hasColumn('cities', 'region_id')) {
                    $this->moveRegionIdColumn();
                }

                if (!Schema::hasColumn('cities', 'system_id')) {
                    $table->string('system_id')
                        ->after('region_id')
                        ->comment('Уникальный идентификатор 1С')
                        ->unique();
                }

                if (!Schema::hasColumn('cities', 'region_system_id')) {
                    $table->string('region_system_id')
                        ->after('system_id')
                        ->comment('Уникальный идентификатор региона 1С')
                        ->index();
                }

                if (Schema::hasColumn('cities', 'timezone')) {
                    $table->string('timezone')->nullable()->change();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cities')) {
            Schema::table('cities', function (Blueprint $table) {
                $table->dropColumn([
                    'system_id',
                    'region_system_id',
                ]);
            });
        }
    }

    private function moveRegionIdColumn(): void
    {
        if (Schema::hasColumn('cities', 'region_id')) {
            Schema::table('cities', function (Blueprint $table) {
                $table->dropForeign(['region_id']);
                $table->dropColumn('region_id');
            });

            Schema::table('cities', function (Blueprint $table) {
                $table->foreignId('region_id')
                    ->after('id')
                    ->constrained('regions')
                    ->onDelete('cascade');
            });
        }
    }
};
