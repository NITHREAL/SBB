<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDefaultUserUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // TODO добавить розничного покупателя
//        DB::statement("INSERT IGNORE INTO users (phone, last_name)
//               VALUES ('0000000000', 'Розничный покупатель')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        DB::statement("DELETE
//            FROM users
//            WHERE phone = '0000000000'
//        ");
    }
}
