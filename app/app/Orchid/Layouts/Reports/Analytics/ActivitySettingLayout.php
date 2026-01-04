<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Reports\Analytics;

use Orchid\Screen\Fields\DateRange;
use Orchid\Screen\Layouts\Rows;

class ActivitySettingLayout extends Rows
{
    protected function fields(): array
    {
       return [
           DateRange::make('created_at')
               ->title('Дата создания'),
       ];
    }
}
