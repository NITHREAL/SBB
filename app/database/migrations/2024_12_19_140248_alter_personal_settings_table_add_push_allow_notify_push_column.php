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
            Schema::hasTable('personal_settings')
            && !Schema::hasColumn('personal_settings', 'allow_notify_push')
        ) {
            Schema::table('personal_settings', function (Blueprint $table) {
               $table->boolean('allow_notify_push')->default(false)->after('allow_notify');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('personal_settings')
            && Schema::hasColumn('personal_settings', 'allow_notify_push')
        ) {
            Schema::table('personal_settings', function (Blueprint $table) {
                $table->dropColumn('allow_notify_push');
            });
        }
    }
};
