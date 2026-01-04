<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class AlterOrdersTableSeedRequestFromColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'request_from')) {
            // TODO если будет необходимо - добавить после появления заказов
//            Artisan::call(
//                'db:seed',
//                ['--class' => \Database\Seeders\OrderRequestFromColumnSeeder::class]
//            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {

    }
}
