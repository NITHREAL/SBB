<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOnlinePaymentsTableAddBatchColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('order_payment') && !Schema::hasColumn('order_payment', 'batch')) {
            Schema::table('order_payment', function (Blueprint $table) {
                $table->unsignedBigInteger('batch')->nullable();
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
        if (Schema::hasTable('order_payment') && Schema::hasColumn('order_payment', 'batch')) {
            Schema::table('order_payment', function (Blueprint $table) {
                $table->dropColumn('batch');
            });
        }
    }
}
