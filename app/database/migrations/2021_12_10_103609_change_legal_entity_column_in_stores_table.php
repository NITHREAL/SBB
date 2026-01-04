<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLegalEntityColumnInStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropConstrainedForeignId('legal_entity_id');
            $table->string('legal_entity_system_id')->after('system_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('legal_entity_system_id');
            $table->foreignId('legal_entity_id')
                ->after('system_id')
                ->nullable()
                ->references('id')
                ->on('legal_entities')
                ->nullOnDelete();
        });
    }
}
