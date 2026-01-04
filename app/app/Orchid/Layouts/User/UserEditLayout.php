<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Domain\User\Enums\UserSexEnum;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('user.first_name')
                ->type('text')
                ->max(255)
                ->title(__('admin.user.first_name'))
                ->placeholder(__('admin.user.first_name')),

            Input::make('user.last_name')
                ->type('text')
                ->max(255)
                ->title(__('admin.user.last_name'))
                ->placeholder(__('admin.user.last_name')),

            Select::make('user.sex')
                ->title('admin.user.sex')
                ->options(UserSexEnum::toArray()),

            Input::make('user.email')
                ->type('email')
              //  ->required()
                ->title(__('admin.user.email'))
                ->placeholder(__('admin.user.email'))
                ->autocomplete('new-email'),

            Input::make('user.birthdate')
                ->type('date')
              //  ->required()
                ->title(__('admin.user.birthday'))
                ->placeholder(__('admin.user.birthday')),

            Input::make('user.phone')
                ->type('tel')
                ->mask('+7 (999) 999-99-99')
                ->required()
                ->title(__('admin.user.phone'))
                ->placeholder(__('admin.user.phone')),
        ];
    }
}
