<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreScheduleWeekdaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_schedule_weekdays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade');
            $table->enum('week_day', [
                'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'
            ]);
            $table->time('from')->nullable();
            $table->time('to')->nullable();
            $table->boolean('not_working')
                ->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_schedule_weekdays');
    }
}
