<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Bonus;

use App\Orchid\Helpers\TD\ID;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class BonusLevelListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'bonusLevels';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            ID::make(),
            TD::make('number', __('platform.bonus.Number')),
            TD::make('title', 'Название'),
            TD::make('description', 'Описание'),
            TD::make('min_bonus_points', __('platform.bonus.Minimum Bonus Points')),
            TD::make('max_bonus_points', __('platform.bonus.Maximum Bonus Points')),
        ];
    }
}
