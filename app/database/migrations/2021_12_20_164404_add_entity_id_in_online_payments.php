<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntityIdInOnlinePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_payments', function (Blueprint $table) {
            $table->foreignId('entity_id')
                ->after('id')
                ->nullable()
                ->references('id')
                ->on('legal_entities')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('entity_id');
        });
    }
}
