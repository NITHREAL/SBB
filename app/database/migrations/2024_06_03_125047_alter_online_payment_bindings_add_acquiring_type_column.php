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
        if (
            Schema::hasTable('online_payment_bindings')
            && !Schema::hasColumn('online_payment_bindings', 'acquiring_type')
        ) {
            Schema::table('online_payment_bindings', function (Blueprint $table) {
                $table->string('acquiring_type')->after('is_default');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('online_payment_bindings')
            && Schema::hasColumn('online_payment_bindings', 'acquiring_type')
        ) {
            Schema::table('online_payment_bindings', function (Blueprint $table) {
                $table->dropColumn('acquiring_type');
            });
        }
    }
};
