<?php

namespace Domain\Notification\Services;

use Domain\Notification\DTO\MassNotificationDTO;
use Domain\Notification\Enum\NotificationRecipientTypeEnum;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use Infrastructure\Notifications\CustomInternalNotification;
use Infrastructure\Services\SMS\Sender\SmsApiInterface;
use Infrastructure\Services\SMS\Sender\SmsMessageInterface;

class MassNotificationService
{
    private int $massNotificationDelay = 3; // секунд

    public function __construct(
        protected SmsApiInterface $smsApi,
        protected SmsMessageInterface $smsMessage
    ) {
    }

    public function sendAudienceMassNotification(object $audience, MassNotificationDTO $dto, string $massNotificationId): void
    {
        $audience->users()->chunk(200, function ($users) use ($dto, $massNotificationId, $audience) {
            $delay = now()->addSeconds($this->massNotificationDelay);

                Notification::send(
                    $users,
                    (new CustomInternalNotification(
                        $dto->title,
                        $dto->text,
                        $dto->url,
                        $dto->deeplink,
                    ))->delay($delay)
                        ->withAdditionalData([
                            'mass_notification_id' => $massNotificationId,
                            'recipientType' => NotificationRecipientTypeEnum::audience()->value,
                            'audienceId' => $audience->id
                        ])
                );

        });
    }

    public function sendUsersMassNotification(Collection|array $users, MassNotificationDTO $dto, string $massNotificationId): void
    {
        $delay = now()->addSeconds($this->massNotificationDelay);

        foreach ($users as $user) {
            Notification::send(
                $user,
                (new CustomInternalNotification(
                    $dto->title,
                    $dto->text,
                    $dto->url,
                    $dto->deeplink,
                ))->delay($delay)
                    ->withAdditionalData([
                        'mass_notification_id' => $massNotificationId,
                        'recipientType' => count($users) > 1 ? NotificationRecipientTypeEnum::custom()->value : NotificationRecipientTypeEnum::personalized()->value,
                        'userId' => $user->id,
                        'userIds' => count($users) > 1 ? $users->pluck('id')->toArray() : null,
                    ])
            );
        }
    }
}
