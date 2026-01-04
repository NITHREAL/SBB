<?php

namespace App\Orchid\Layouts\References\LegalEntity;

use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\TitleFilter;
use Orchid\Screen\Layouts\Selection;

class LegalEntityFiltersLayout extends Selection
{
    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            IdFilter::class,
            TitleFilter::class,
        ];
    }
}
