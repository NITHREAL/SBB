<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Rows;

class UserNotificationsLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            CheckBox::make('user.settings.allow_notify')
                ->title(__('admin.user.allow_notify'))
                ->sendTrueOrFalse()
                ->disabled(),
            CheckBox::make('user.settings.allow_notify_email')
                ->title(__('admin.user.allow_notify_email'))
                ->sendTrueOrFalse()
                ->disabled(),
            CheckBox::make('user.settings.allow_notify_sms')
                ->title(__('admin.user.allow_notify_sms'))
                ->sendTrueOrFalse()
                ->disabled(),
        ];
    }
}
