<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerQuestionnairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmer_questionnaires', function (Blueprint $table) {
            $table->id();
            $table->string('fio');
            $table->string('phone');
            $table->string('email');
            $table->string('your_products');
            $table->string('about_you');
            $table->string('your_business');
            $table->string('your_learn');
            $table->string('legal_about');
            $table->string('legal_name');
            $table->string('your_employees');
            $table->string('production_volume');
            $table->string('main_directions');
            $table->string('your_location');
            $table->string('need_workshop');
            $table->string('valid_documents');
            $table->string('responsible_for_quality');
            $table->string('cause');
            $table->string('difficulty');
            $table->string('progress');
            $table->string('future');
            $table->string('plans_future');
            $table->string('comment');
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
        Schema::dropIfExists('farmer_questionnaires');
    }
}
