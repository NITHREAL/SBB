<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->string('system_id')
                ->after('id')
                ->unique()
                ->nullable();
            $table->boolean('active')
                ->default(true)
                ->after('system_id');
            $table->boolean('main')
                ->default(false)
                ->after('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropColumn('system_id');
            $table->dropColumn('active');
            $table->dropColumn('main');
        });
    }
}
