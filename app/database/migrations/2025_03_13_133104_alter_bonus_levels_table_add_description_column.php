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
        if (Schema::hasTable('bonus_levels')) {
            if (!Schema::hasColumn('bonus_levels', 'description')) {
                Schema::table('bonus_levels', function (Blueprint $table) {
                    $table->text('description')->nullable()->after('title');
                });
            }

            if (!Schema::hasColumn('bonus_levels', 'loyalty_id')) {
                Schema::table('bonus_levels', function (Blueprint $table) {
                    $table->string('loyalty_id')->after('id');
                });
            }

            if (Schema::hasColumn('bonus_levels', 'number')) {
                Schema::table('bonus_levels', function (Blueprint $table) {
                    $table->string('number')->nullable()->change();
                });
            }

            if (Schema::hasColumn('bonus_levels', 'title')) {
                Schema::table('bonus_levels', function (Blueprint $table) {
                    $table->string('title')->nullable()->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('bonus_levels')) {
            if (Schema::hasColumn('bonus_levels', 'loyalty_id')) {
                Schema::table('bonus_levels', function (Blueprint $table) {
                    $table->dropColumn('loyalty_id');
                });
            }

            if (Schema::hasColumn('bonus_levels', 'description')) {
                Schema::table('bonus_levels', function (Blueprint $table) {
                    $table->dropColumn('description');
                });
            }

            if (Schema::hasColumn('bonus_levels', 'title')) {
                Schema::table('bonus_levels', function (Blueprint $table) {
                    $table->string('title')->change();
                });
            }

            if (Schema::hasColumn('bonus_levels', 'number')) {
                Schema::table('bonus_levels', function (Blueprint $table) {
                    $table->integer('number')->change();
                });
            }
        }
    }
};
