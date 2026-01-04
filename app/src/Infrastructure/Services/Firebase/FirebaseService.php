<?php

namespace Infrastructure\Services\Firebase;

use Illuminate\Support\Facades\Log;
use Infrastructure\Services\Firebase\DTO\FirebaseNotificationDTO;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Orchid\Support\Facades\Toast;

class FirebaseService
{

    private Messaging $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    /**
     * @throws MessagingException
     * @throws FirebaseException
     */
    public function sendMulticastNotifications(FirebaseNotificationDTO $notificationDTO): void
    {
        $deviceTokens = $notificationDTO->getDeviceTokens();

        if (empty($deviceTokens)) {
            Toast::error('У пользователей нет токенов для получения уведомлений.');
            return;
        }

        $message = CloudMessage::new()->withNotification(Notification::create(
            $notificationDTO->getTitle(),
            $notificationDTO->getBody())
        )
            ->withData($notificationDTO->getData());

        $report = $this->messaging->sendMulticast($message, $deviceTokens);

        Log::info(
            sprintf(
                'Произошла отправка push уведомлений. Успешных: [%s]. С ошибкой: [%s] ',
                $report->successes()->count(),
                $report->failures()->count(),
            ),
        );

        if ($report->hasFailures()) {
            foreach ($report->failures()->getItems() as $failure) {
                Log::error(
                    sprintf(
                        'Ошибка при отправке push уведомления. [%s] ',
                        $failure->error()->getMessage(),
                    ),
                );
            }
        }
    }
}
