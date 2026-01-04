<?php

namespace Infrastructure\Notifications\Channels\Sms;

use Illuminate\Notifications\Notification;

interface SmsNotificationChannelInterface
{
    public function send(mixed $notifiable, Notification $notification): ?array;
}
