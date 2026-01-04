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
        if (Schema::hasTable('online_payment_bindings')) {
            if (!Schema::hasColumn('online_payment_bindings', 'first_chars')) {
                Schema::table('online_payment_bindings', function (Blueprint $table) {
                    $table->string('first_chars')->nullable()->after('acquiring_type');
                });
            }

            if (!Schema::hasColumn('online_payment_bindings', 'last_chars')) {
                Schema::table('online_payment_bindings', function (Blueprint $table) {
                    $table->string('last_chars')->nullable()->after('first_chars');
                });
            }

            if (!Schema::hasColumn('online_payment_bindings', 'card_type')) {
                Schema::table('online_payment_bindings', function (Blueprint $table) {
                    $table->string('card_type')->nullable()->after('last_chars');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('online_payment_bindings')) {
            if (Schema::hasColumn('online_payment_bindings', 'first_chars')) {
                Schema::table('online_payment_bindings', function (Blueprint $table) {
                    $table->dropColumn('first_chars');
                });
            }

            if (Schema::hasColumn('online_payment_bindings', 'last_chars')) {
                Schema::table('online_payment_bindings', function (Blueprint $table) {
                    $table->dropColumn('last_chars');
                });
            }

            if (Schema::hasColumn('online_payment_bindings', 'card_type')) {
                Schema::table('online_payment_bindings', function (Blueprint $table) {
                    $table->dropColumn('card_type');
                });
            }
        }
    }
};
