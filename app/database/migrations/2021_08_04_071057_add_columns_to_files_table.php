<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->string('system_id')
                ->nullable()
                ->after('id');
            $table->boolean('active')
                ->after('system_id')
                ->default(true);
            $table->boolean('main')
                ->after('active')
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
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('system_id');
            $table->dropColumn('active');
            $table->dropColumn('main');
        });
    }
}
