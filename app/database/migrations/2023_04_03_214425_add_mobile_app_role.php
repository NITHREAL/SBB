<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMobileAppRole extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // TODO обавить после появления ролей орчида
//        $role = \Orchid\Platform\Models\Role::query()->where(['slug' => 'mobile_app'])->first();
//
//        if ($role) {
//            return true;
//        }
//
//
//
//        $role = new \Orchid\Platform\Models\Role();
//        $role->name = 'Роль МП';
//        $role->slug = 'mobile_app';
//
//        $permissions = new \Illuminate\Support\Collection();
//        $permissions['platform.coupons.category.list'] = true;
//        $permissions['platform.stores.list'] = true;
//        $permissions['platform.mass_notifications'] = true;
//        $permissions['platform.index'] = true;
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
