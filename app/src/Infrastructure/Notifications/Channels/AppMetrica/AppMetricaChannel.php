<?php

namespace Infrastructure\Notifications\Channels\AppMetrica;

use Illuminate\Notifications\Notification;
use Infrastructure\Notifications\PushNotification;
use Infrastructure\Services\SMS\Sender\SmsRu\Exceptions\CouldNotSendNotification;
use Infrastructure\Services\Yandex\AppMetrica\AppMetricaApi;

class AppMetricaChannel
{
    public function __construct(
        protected AppMetricaApi $appMetricaApi,
    ) {
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  PushNotification  $notification
     *
     * @throws CouldNotSendNotification
     *
     * @return array|null
     */
    public function send(mixed $notifiable, PushNotification $notification): ?array
    {
        $message = $notification->toAppMetrica($notifiable);

        $params = $message->toArray();

        return $this->appMetricaApi->pushSendBatch($params);
    }

    /**
     * Gets a list of tokens from the given notifiable.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @return string[]
     */
    protected function getRecipients($notifiable, Notification $notification): array
    {
        $to = $notifiable->routeNotificationFor('push', $notification);

        if (empty($to)) {
            return [];
        }

        return is_array($to) ? $to : [$to];
    }
}
