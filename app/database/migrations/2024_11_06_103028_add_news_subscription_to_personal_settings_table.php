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
            && !Schema::hasColumn('personal_settings', 'news_subscription')
        ) {
            Schema::table('personal_settings', function (Blueprint $table) {
                $table->boolean('news_subscription')
                    ->nullable(false)
                    ->default(false)
                    ->after('allow_phone_calls');
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
            && Schema::hasColumn('personal_settings', 'news_subscription')
        ) {
            Schema::table('personal_settings', function (Blueprint $table) {
                $table->dropColumn('news_subscription');
            });
        }
    }
};
