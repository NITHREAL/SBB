<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('files');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('system_id')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('main')->default(false);
            $table->string('filename');
            $table->bigInteger('filesize');
            $table->string('filepath');
            $table->string('mime_type');
            $table->numericMorphs('entity');
            $table->timestamps();
        });
    }
}
