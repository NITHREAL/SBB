<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPolygonIdToStoreScheduleDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_schedule_dates', function (Blueprint $table) {
            $table->foreignId('polygon_type_id')
                ->after('id')
                ->nullable()
                ->references('id')
                ->on('polygon_types')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_schedule_dates', function (Blueprint $table) {
            $table->dropConstrainedForeignId('polygon_type_id');
        });
    }
}
