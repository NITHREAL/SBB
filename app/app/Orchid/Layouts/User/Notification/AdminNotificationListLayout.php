<?php

namespace app\Orchid\Layouts\User\Notification;

use App\Orchid\Core\TD;
use Illuminate\Notifications\DatabaseNotification;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

use Illuminate\Support\Arr;

class AdminNotificationListLayout extends Table
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
                    return Arr::get($notification->data, 'title', 'no title');
                }),
            TD::make('link', __('admin.notifications.link'))
                ->render(function (DatabaseNotification $notification) {
                    $url = $notification->data['downloadUrl'];
                    return !empty($url)
                        ? Link::make('Скачать')
                            ->href($url)
                        : '';
                }),
            TD::make('message', __('admin.notifications.body'))
                ->render(function (DatabaseNotification $notification) {
                    return Arr::get($notification->data, 'message', __('admin.notifications.no_message_content'));
                }),
            TD::make('created_at', __('admin.notifications.created_at'))
                ->alignCenter()
                ->render(function (DatabaseNotification $notification) {
                    return $notification->created_at ?? 'No date';
                }),
        ];
    }
}


