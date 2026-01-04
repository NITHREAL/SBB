<?php

namespace App\Providers;

use Domain\User\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    protected function gate(): void
    {
        Gate::define('viewHorizon', static function (User $user) {
            return $user->email === config('auth.admin.email');
        });
    }
}
