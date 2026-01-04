<?php

namespace App\Orchid\Layouts\Support;

use App\Orchid\Filters\Support\TypeFilter;
use Orchid\Screen\Layouts\Selection;

class SupportFilterLayout extends Selection
{
    /**
     * @inheritDoc
     */
    public function filters(): array
    {
        return [
            TypeFilter::class,
        ];
    }
}
