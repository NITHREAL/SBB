<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        $configPermissions = config('platform.permissions');
        $permissions = ItemPermission::group(__('admin.permissions.modules'));

        foreach ($configPermissions as $item) {
            $permissions->addPermission($item['slug'], $item['name']);
        }

        $permissions->addPermission('easypasscode', 'Упрощенная авторизация');

        $dashboard->registerPermissions($permissions);
    }
}
