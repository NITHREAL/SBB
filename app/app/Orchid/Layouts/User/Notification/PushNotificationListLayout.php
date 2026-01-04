<?php

namespace App\Orchid\Layouts\User\Notification;

use App\Orchid\Core\TD;
use Illuminate\Notifications\DatabaseNotification;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class PushNotificationListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'notifications';

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
                    return $notification->data['text']?? $notification->data['message'] ?? $notification->data['body'];
                }),
            TD::make('created_at', __('admin.notifications.created_at'))
                ->alignCenter()
                ->sort()
                ->render(function (DatabaseNotification $notification) {
                    return $notification->created_at;
                }),
        ];
    }
}
