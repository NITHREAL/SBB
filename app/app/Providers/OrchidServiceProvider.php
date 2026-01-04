<?php

namespace App\Providers;

use App\Orchid\Macros\Cell\ActionMacro;
use App\Orchid\Macros\Cell\BoolMacro;
use App\Orchid\Macros\Cell\EnumMacro;
use App\Orchid\Macros\Cell\PreviewMacro;
use Illuminate\Support\ServiceProvider;
use Orchid\Screen\TD;

class OrchidServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        TD::macro('actions', ActionMacro::macro());
        TD::macro('bool', BoolMacro::macro());
        TD::macro('enum', EnumMacro::macro());
        TD::macro('preview', PreviewMacro::macro());
    }
}
