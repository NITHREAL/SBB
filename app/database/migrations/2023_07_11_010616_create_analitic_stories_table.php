<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnaliticStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analytic_stories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->index();
            $table->integer('count_sales',)->default(0)->comment('количество продаж');
            $table->double('sum_sales', 15, 2)->default(0)->comment('сумма продаж');
            $table->double('average_check', 15, 2)->default(0)->comment('средний чек');
            $table->integer('accrued_points',)->default(0)->comment('начисленные баллы');
            $table->integer('deducted_points',)->default(0)->comment('вычтенные баллы');
            $table->integer('amount_gifts',)->default(0)->comment('количество подарков');
            $table->integer('new_users',)->default(0);
            $table->date('date_activity',)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analytic_stories');
    }
}
