<?php

namespace App\Orchid\Layouts\Shop\PromoAction;

use App\Orchid\Core\Actions\Activate;
use App\Orchid\Core\Actions\Edit;
use App\Orchid\Helpers\TD\Active;
use App\Orchid\Helpers\TD\ID;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PromoActionListLayout extends Table
{
    protected $target = 'promoActions';

    protected function columns(): array
    {
        return [
            ID::make()->sort(),
            Active::make()->sort(),
            TD::make('title', 'Название')->sort(),
            TD::make('active_from', 'Дата с')->sort()
                ->render(fn(object $item) => $item->formattedActiveFrom ?? '-'),
            TD::make('active_to', 'Дата по')->sort()
                ->render(fn(object $item) => $item->formattedActiveTo ?? '-'),
            TD::make('sort', 'Сортировка')->sort(),
            TD::make()->actions([
                new Activate(),
                new Edit('platform.promo-actions.edit'),
            ]),
        ];
    }
}
