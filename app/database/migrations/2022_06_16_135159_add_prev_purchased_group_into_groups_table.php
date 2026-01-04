<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrevPurchasedGroupIntoGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //TODO если будет необходимо добавить сидер после появления подборок
        /*
        \App\Models\Group::query()->updateOrCreate([
            'slug' => 'previous-purchased'
        ], [
            'system_id' => 'prev-purchased-group_non_system_id',
            'title' => 'Вы покупали ранее',
            'active' => true,
            'sort' => 100
        ]);
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //TODO если будет необходимо добавить сидер после появления подборок
//        \App\Models\Group::query()
//            ->where('slug', 'previous-purchased')
//            ->delete();
    }
}
