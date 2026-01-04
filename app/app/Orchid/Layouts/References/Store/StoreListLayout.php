<?php

namespace App\Orchid\Layouts\References\Store;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\Active;
use App\Orchid\Helpers\TD\ID;
use App\Orchid\Helpers\TD\Sort;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class StoreListLayout extends Table
{
    /**
     * @var string
     */
    protected $target = 'stores';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            ID::make()
                ->sort(),
            Active::make()
                ->sort(),
            TD::make('city.title', __('admin.store.locality'))
                ->sort(),
            TD::make('title', __('admin.title'))
                ->sort(),
            TD::make('is_dark_store', 'Служебный')
                ->render(function ($store) {
                    return $store->is_dark_store ? 'Да' : 'Нет';
                })
                ->alignCenter()
                ->sort(),
            Sort::make()
                ->sort(),
            TD::make()->actions($this->getActions()),
        ];
    }

    private function getActions(): array
    {
        /** @var $user User */
        $user = Auth::user();
        $actions = [
            new Actions\Edit('platform.stores.edit')
        ];

        if ($user->hasAccess('activate_store')) {
            array_unshift($actions, new Actions\Activate());
        }

        return $actions;
    }
}
