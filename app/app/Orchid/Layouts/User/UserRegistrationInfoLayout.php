<?php

declare(strict_types=1);

namespace app\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class UserRegistrationInfoLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            DateTimer::make('user.created_at')
                ->title(__('admin.user.created_at'))
                ->disabled()
                ->format('d-m-Y')
            ,
            Input::make('user.registration_type')
                ->readonly()
                ->title(__('admin.user.registration_type')),
        ];
    }
}
