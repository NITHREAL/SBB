<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrderContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_contacts', function (Blueprint $table) {
            $table->foreignId('city_id')
                ->after('order_id')
                ->nullable()
                ->references('id')
                ->on('cities')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_contacts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('city_id');
        });
    }
}
