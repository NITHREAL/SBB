<?php

namespace App\Orchid\Layouts\Shop\Promo;

use App\Orchid\Fields\Matrix;
use Domain\User\Models\User;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class PromoUsersLayout extends Rows
{
    protected string $target = 'promo.users';

    protected function fields(): array
    {
        return [
            Matrix::make('promo.users')
                ->columns([
                    __('admin.promo.user') => 'id',
                    __('admin.promo.max_uses') => 'pivot.max_uses'
                ])
                ->fields([
                    'id' => Relation::make()
                        ->fromModel(User::class, 'id')
                        ->displayAppend('phone')
                        ->searchColumns('first_name', 'last_name', 'phone', 'email'),
                    'pivot.max_uses' => Input::make()
                        ->type('number')
                        ->step(1)
                        ->min(0)
                ])
                ->addRowText(__('admin.promo.users_add')),
        ];
    }
}
