<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoryMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('story_metadata', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('story_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('card_number')->nullable();
            $table->dateTime('view_date')->nullable();
            $table->string('view_duration')->nullable();
            $table->boolean('was_clicked')->nullable();
            $table->boolean('moved_to_next')->nullable();

            $table->foreign('story_id')
                ->references('id')
                ->on('stories')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('story_metadata');
    }
}
