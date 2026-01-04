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
            Schema::hasTable('online_payments')
            && Schema::hasColumn('online_payments', 'form_url')
        ) {
            Schema::table('online_payments', function (Blueprint $table) {
                $table->text('form_url')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('online_payments')
            && Schema::hasColumn('online_payments', 'form_url')
        ) {
            Schema::table('online_payments', function (Blueprint $table) {
                $table->string('form_url')->nullable()->change();
            });
        }
    }
};
