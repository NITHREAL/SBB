<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntercomFieldToOrderContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_contacts', function (Blueprint $table) {
            $table->string('intercom')->nullable()->default(null);
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
            $table->dropColumn('intercom');
        });
    }
}
