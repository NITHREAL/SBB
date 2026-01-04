<?php

namespace App\Orchid\Layouts\Shop\Promo;

use App\Orchid\Actions\EnableMobile;
use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\Active;
use App\Orchid\Helpers\TD\ID;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PromoListLayout extends Table
{
    protected $target = 'promos';

    protected function columns(): array
    {
        return [
            ID::make(),
            Active::make(),
            TD::make('title', __('admin.promo.title')),
            TD::make('code', __('admin.promo.code'))
                ->sort()
                ->width(120),
            TD::make('discount', __('admin.promo.discount'))
                ->sort(),
            TD::make('free_delivery', __('admin.promo.free_delivery'))
                ->render(function ($promo) {
                    return $promo->free_delivery ? 'Да' : 'Нет';
                })
                ->sort(),
            TD::make('expires_in', __('admin.promo.expires_in'))
                ->sort(),
            TD::make()->actions([
                new Actions\Activate(),
                new EnableMobile(),
                new Actions\Edit('platform.promos.edit'),
            ])
        ];
    }
}
