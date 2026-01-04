<?php

namespace App\Orchid\Layouts\User\Notification;

use App\Orchid\Core\TD;
use Domain\Audience\Models\Audience;
use Domain\Notification\Enum\NotificationRecipientTypeEnum;
use Domain\User\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Orchid\Screen\Layouts\Table;

class MassNotificationListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'notifications';

    protected function subNotFound(): string
    {
        if (count(request()->query()) !== 0) {
            return __('admin.notifications.сhange_filter_or_remove');
        }
        return __('admin.notifications.сreate_оr_check_for_updates');
    }

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('title', __('admin.notifications.title'))
                ->render(function (DatabaseNotification $notification) {
                    return $notification->data['title'];
                }),
            TD::make('body', __('admin.notifications.body'))
                ->render(function (DatabaseNotification $notification) {
                    $label = $notification->data['body'] ?? $notification->data['message'];
                    return $label;
                }),
            TD::make('recipients', __('admin.notifications.recipients'))
                ->render(function (DatabaseNotification $notification) {
                    return $this->getRecipientType($notification);
                }),
            TD::make('created_at', __('admin.created_at'))
                ->alignCenter()
                ->sort()
                ->render(function (DatabaseNotification $notification) {
                    return $notification->created_at->format('d-m-Y H:i:s');
                }),
        ];
    }

    private function getRecipientType(DatabaseNotification $notification): string
    {
        $recipientType = $notification->data['recipientType'] ?? null;
        $recipients = '';

        switch ($recipientType) {
            case NotificationRecipientTypeEnum::audience()->value:
                $audience = Audience::find($notification->data['audienceId']);
                if ($audience) {
                    $recipients = __('admin.notifications.audience') . ': ' . $audience->title;
                }
                break;
            case NotificationRecipientTypeEnum::personalized()->value:
                $user = User::find($notification->data['userId']);
                if ($user) {
                    $recipients = $user->first_name;
                }
                break;
            case NotificationRecipientTypeEnum::custom()->value:
                $userIds = $notification->data['userIds'];
                $users = User::whereIn('id', $userIds)->get();
                $recipients = $users->pluck('first_name')->join(', ');
                break;
        }

        return $recipients;
    }
}
