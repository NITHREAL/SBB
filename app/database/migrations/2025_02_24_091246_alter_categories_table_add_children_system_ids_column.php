<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'children_system_ids')) {
            Schema::table('categories', function ($table) {
                $table->jsonb('children_system_ids')->nullable()->after('special_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'children_system_ids')) {
            Schema::table('categories', function ($table) {
                $table->dropColumn('children_system_ids');
            });
        }
    }
};
