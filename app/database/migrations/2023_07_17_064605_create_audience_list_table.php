<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudienceListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audience_list', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('audience_id')->index();
            $table->bigInteger('user_id');
            $table->timestamps();
            $table->softDeletes()->index();

            $table->unique(['audience_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audience_list');
    }
}
