<?php

use Database\Seeders\CityRegionFiasIdSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('cities')) {
            if (!Schema::hasColumn('cities', 'region_fias_id')) {
                Schema::table('cities', function (Blueprint $table) {
                    $table->string('region_fias_id')->nullable()->after('fias_id');
                });
            }

            if (Schema::hasColumn('cities', 'region_fias_id')) {
                Artisan::call('db:seed', ['--class' => CityRegionFiasIdSeeder::class]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('cities') && Schema::hasColumn('cities', 'region_fias_id')) {
            Schema::table('cities', function (Blueprint $table) {
               $table->dropColumn('region_fias_id');
            });
        }
    }
};
