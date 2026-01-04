<?php

namespace App\Orchid\Layouts\References\LegalEntity;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\ID;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class LegalEntityListLayout extends Table
{
    protected $target = 'legal_entities';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            ID::make(),
            TD::make('title', __('admin.title'))->sort(),
            TD::make('sber_login', __('admin.legal_entity.sber_login'))->sort(),
            TD::make()->actions([
                new Actions\Edit('platform.legal_entities.edit'),
            ])
        ];
    }
}
