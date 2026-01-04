<?php

namespace Infrastructure\Notifications\Channels\Sms;

use DateTimeInterface;
use Illuminate\Notifications\Notification;
use Infrastructure\Services\SMS\Sender\SmsApiInterface;
use Infrastructure\Services\SMS\Sender\SmsMessageInterface;
use Infrastructure\Services\SMS\Sender\SmsRu\Exceptions\CouldNotSendNotification;
use Infrastructure\Services\SMS\Sender\SmsRu\SmsRuMessage;

class SmsRuNotificationChannel implements SmsNotificationChannelInterface
{
    private const CONTENT_LIMIT = 800;

    protected string $notificationRouteName = 'sms';

    public function __construct(
        protected SmsApiInterface $smsApi,
    ) {
    }

    /**
     * @param mixed $notifiable
     * @param Notification $notification
     * @return array|null
     * @throws CouldNotSendNotification
     */
    public function send(mixed $notifiable, Notification $notification): ?array
    {
        if (! ($to = $this->getRecipients($notifiable, $notification))) {
            return null;
        }

        $message = $notification->{'getMessage'}($notifiable);

        if (is_string($message)) {
            $message = new SmsRuMessage($message);
        }

        $params = $this->prepareMessageParams($to, $message);

        return $this->smsApi->send($params);
    }

    /**
     * Gets a list of phones from the given notifiable.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @return string[]
     */
    protected function getRecipients(mixed $notifiable, Notification $notification): array
    {
        $to = $notifiable->routeNotificationFor($this->notificationRouteName, $notification);

        if (empty($to)) {
            return [];
        }

        return is_array($to) ? $to : [$to];
    }

    /**
     * @throws CouldNotSendNotification
     */
    protected function prepareMessageParams(array $recipients, SmsMessageInterface $message): array
    {
        if (mb_strlen($message->content) > self::CONTENT_LIMIT) {
            throw CouldNotSendNotification::contentLengthLimitExceeded();
        }

        $params = [
            'to'    => implode(',', $recipients),
            'msg'   => $message->content,
        ];

        if ($message->from) {
            $params['from'] = $message->from;
        }

        if ($message->sendAt instanceof DateTimeInterface) {
            $params['time'] = '0'.$message->sendAt->getTimestamp();
        }

        return $params;
    }
}
