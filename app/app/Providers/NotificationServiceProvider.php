<?php

namespace App\Providers;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Notifications\Channels\Sms\SmsNotificationChannelInterface;
use Infrastructure\Notifications\Channels\Sms\SmsRuNotificationChannel;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            SmsNotificationChannelInterface::class,
            SmsRuNotificationChannel::class,
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        Notification::extend('sms', function ($app) {
            return $app->make(SmsNotificationChannelInterface::class);
        });
    }
}
