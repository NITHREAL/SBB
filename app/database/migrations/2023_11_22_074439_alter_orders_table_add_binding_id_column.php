<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrdersTableAddBindingIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (
            Schema::hasTable('orders')
            && !Schema::hasColumn('orders', 'binding_id')
            && Schema::hasTable('online_payment_bindings')
        ) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('binding_id')->nullable()->after('pay_url');

                $table->foreign('binding_id')
                    ->references('id')
                    ->on('online_payment_bindings');
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
            Schema::hasTable('orders')
            && Schema::hasColumn('orders', 'binding_id')
        ) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign('orders_binding_id_foreign');
                $table->dropColumn('binding_id');
            });
        }
    }
}
