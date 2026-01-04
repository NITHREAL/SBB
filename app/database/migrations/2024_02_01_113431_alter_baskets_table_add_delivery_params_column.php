<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBasketsTableAddDeliveryParamsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('baskets') && !Schema::hasColumn('baskets', 'delivery_params')) {
            Schema::table('baskets', function (Blueprint $table) {
                $table->json('delivery_params')->nullable()->after('token');
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
        if (Schema::hasTable('baskets') && Schema::hasColumn('baskets', 'delivery_params')) {
            Schema::table('baskets', function (Blueprint $table) {
                $table->dropColumn('delivery_params');
            });
        }
    }
}
