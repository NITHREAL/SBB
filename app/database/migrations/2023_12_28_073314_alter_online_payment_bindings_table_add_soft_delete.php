<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOnlinePaymentBindingsTableAddSoftDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('online_payment_bindings')) {
            Schema::table('online_payment_bindings', function (Blueprint $table) {
                $table->softDeletes();
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
        if (
            Schema::hasTable('online_payment_bindings')
            && Schema::hasColumn('online_payment_bindings', 'deleted_at')
        ) {
            Schema::table('online_payment_bindings', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
}
