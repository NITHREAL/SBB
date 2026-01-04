<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StoryPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('story_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')
                ->constrained()
                ->onDelete('cascade')
            ;
            $table->string('image');
            $table->integer('position');
            $table->string('label')->nullable();
            $table->string('title')->nullable();
            $table->text('text')->nullable();
            $table->string('type');
            $table->string('target_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('story_pages');
    }
}
