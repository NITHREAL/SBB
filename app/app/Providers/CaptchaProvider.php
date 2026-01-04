<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Infrastructure\Services\Captcha\CaptchaInterface;
use Infrastructure\Services\Captcha\RecaptchaClient;
use Infrastructure\Services\SMS\Sender\SmsApiInterface;
use Infrastructure\Services\SMS\Sender\SmsMessageInterface;
use Infrastructure\Services\SMS\Sender\SmsRu\SmsRuApi;
use Infrastructure\Services\SMS\Sender\SmsRu\SmsRuMessage;

class CaptchaProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            CaptchaInterface::class,
            fn() => new RecaptchaClient(config('services.captcha')),
        );
    }

    /**
     * @return void
     */
    public function boot()
    {
    }
}
