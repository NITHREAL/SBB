<?php

namespace App\Orchid\Layouts\Content\Story;

use App\Orchid\Filters\ActiveFilter;
use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\TitleFilter;
use Orchid\Screen\Layouts\Selection;

class StoryFiltersLayout extends Selection
{
    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            IdFilter::class,
            TitleFilter::class,
            ActiveFilter::class,
        ];
    }
}
