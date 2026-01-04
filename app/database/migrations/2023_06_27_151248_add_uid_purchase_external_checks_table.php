<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUidPurchaseExternalChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('TRUNCATE TABLE external_checks;');
        Schema::table('external_checks', function (Blueprint $table) {
            $table->string('uid_purchase')->unique()->after('id')->comment('Уникальный номер чека');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_checks', function (Blueprint $table) {
            $table->dropColumn('uid_purchase');
        });
    }
}
