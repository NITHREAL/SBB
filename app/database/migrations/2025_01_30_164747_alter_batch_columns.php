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
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'batch')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->uuid('batch')->nullable()->change();

                $table->index(['batch']);
            });
        }

        if (Schema::hasTable('order_payment') && Schema::hasColumn('order_payment', 'batch')) {
            Schema::table('order_payment', function (Blueprint $table) {
                $table->uuid('batch')->nullable()->change();

                $table->index(['batch']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('orders')
            && Schema::hasColumn('orders', 'batch')
        ) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('batch')->nullable()->change();

                $table->dropIndex(['batch']);
            });
        }


        if (Schema::hasTable('order_payment') && Schema::hasColumn('order_payment', 'batch')) {
            Schema::table('order_payment', function (Blueprint $table) {
                $table->unsignedBigInteger('batch')->nullable()->change();

                $table->dropIndex(['batch']);
            });
        }
    }
};
