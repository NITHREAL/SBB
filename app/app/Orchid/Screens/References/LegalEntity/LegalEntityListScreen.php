<?php

namespace App\Orchid\Screens\References\LegalEntity;

use App\Models\LegalEntity;
use App\Orchid\Core\Actions;
use App\Orchid\Layouts\References\LegalEntity\LegalEntityFiltersLayout;
use App\Orchid\Layouts\References\LegalEntity\LegalEntityListLayout;
use Orchid\Screen\Screen;

class LegalEntityListScreen extends Screen
{
    public $name = '';

    public function query(): array
    {
        $this->name = __('admin.legal_entities');

        $entities = LegalEntity::query()
            ->filtersApplySelection(LegalEntityFiltersLayout::class)
            ->filters()
            ->defaultSort('id', 'desc')
            ->paginate();

        return [
            'legal_entities' => $entities
        ];
    }

    public function layout(): array
    {
        return [
            LegalEntityFiltersLayout::class,
            LegalEntityListLayout::class
        ];
    }
}
