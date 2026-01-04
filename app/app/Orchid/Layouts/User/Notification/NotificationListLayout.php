<?php

namespace App\Orchid\Layouts\User\Notification;

use App\Orchid\Core\TD;
use Domain\Notification\Enum\NotificationTypeEnum;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Arr;
use Orchid\Screen\Layouts\Table;

class NotificationListLayout extends Table
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
        // ->sort() из коробки не поддерживается,
        // из коробки идет orderBy('created_at', 'desc')
        //Todo: надо выносить нотификации в отдельную модель,
        return [
            TD::make('id', __('admin.id'))
                ->alignCenter()
                ->cantHide()
                ->render(function (DatabaseNotification $notification) {
                    return $notification->id;
                }),
            TD::make('body', __('admin.notifications.body'))
                ->alignCenter()
                ->render(function (DatabaseNotification $notification) {
                    return Arr::get($notification->data, 'body');
                }),
            TD::make('type', __('admin.notifications.type'))
                ->alignCenter()
                ->render(function (DatabaseNotification $notification) {
                    return NotificationTypeEnum::tryFrom($notification->type)->label;
                }),
            TD::make('read_at', __('admin.notifications.read_at'))
                ->alignCenter()
                ->render(function (DatabaseNotification $notification) {
                    return $notification->read_at;
                }),
            TD::make('created_at', __('admin.created_at'))
                ->alignCenter()
                ->render(function (DatabaseNotification $notification) {
                    return $notification->created_at;
                }),
        ];
    }
}
