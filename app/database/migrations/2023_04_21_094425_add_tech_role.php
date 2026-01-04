<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTechRole extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {

        // TODO обавить после появления ролей орчида
//        $role = \Orchid\Platform\Models\Role::query()->where(['slug' => 'tech'])->first();
//
//        if ($role) {
//            return true;
//        }
//
//
//
//        $role = new \Orchid\Platform\Models\Role();
//        $role->name = 'Тех-роль для МП';
//        $role->slug = 'tech';
//
//        $permissions = new \Illuminate\Support\Collection();
//        $permissions['easypasscode'] = true;
//
//        $role->permissions = $permissions;
//
//        $role->save();
//
//        return true;
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
//        $role = \Orchid\Platform\Models\Role::query()->where(['slug' => 'mobile_app']);
//
//        if ($role) {
//            $role->delete();
//        }
//
//        return true;
    }
}
