<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOnlinePaymentBindingsTableAddDefaultColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (
            Schema::hasTable('online_payment_bindings')
            && !Schema::hasColumn('online_payment_bindings', 'is_default')
        ) {
            Schema::table('online_payment_bindings', function (Blueprint $table) {
                $table->boolean('is_default')->after('expiry_date')->default(false);
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
            && Schema::hasColumn('online_payment_bindings', 'is_default')
        ) {
            Schema::table('online_payment_bindings', function (Blueprint $table) {
                $table->dropColumn('is_default');
            });
        }
    }
}
