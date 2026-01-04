<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserAdressesTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (
            Schema::hasTable('user_addresses')
            && !Schema::hasColumns('user_addresses', ['city_name', 'street', 'house', 'building'])
        ) {
            Schema::table('user_addresses', function (Blueprint $table) {
                $table->string('city_name')->after('address');
                $table->string('street')->after('city_name');
                $table->string('house')->after('street');
                $table->string('building')->nullable()->after('house');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (
            Schema::hasTable('user_addresses')
            && Schema::hasColumns('user_addresses', ['city_name', 'street', 'house', 'building'])
        ) {
            Schema::table('user_addresses', function (Blueprint $table) {
                $table->dropColumn('city_name');
                $table->dropColumn('street');
                $table->dropColumn('house');
                $table->dropColumn('building');
            });
        }
    }
}
