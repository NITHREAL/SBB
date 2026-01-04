<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Services\Yandex\AppMetrica\AppMetricaApi;

class AppMetricaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(AppMetricaApi::class, static function () {
            return new AppMetricaApi(
                config('services.appmetrica.token'),
                config('services.appmetrica.app_id'),
                new Client([
                    'timeout' => 5,
                    'connect_timeout' => 5,
                ])
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
