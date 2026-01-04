<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->unsignedBigInteger('show_audience_id')->nullable()->after('id');

            $table->index(['show_audience_id']);

            $table->foreign('show_audience_id')
                ->references('id')
                ->on('audiences')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dropForeign('promos_show_audience_id_foreign');
            $table->dropIndex('promos_show_audience_id_index');
            $table->dropColumn('show_audience_id');
        });
    }
};
