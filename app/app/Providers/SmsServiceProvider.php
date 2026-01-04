<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Infrastructure\Services\SMS\Sender\SmsApiInterface;
use Infrastructure\Services\SMS\Sender\SmsMessageInterface;
use Infrastructure\Services\SMS\Sender\SmsRu\SmsRuApi;
use Infrastructure\Services\SMS\Sender\SmsRu\SmsRuMessage;

class SmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            SmsApiInterface::class,
            fn() => new SmsRuApi(config('services.smsru')),
        );
        $this->app->bind(
            SmsMessageInterface::class,
            SmsRuMessage::class,
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
