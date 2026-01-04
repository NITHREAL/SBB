<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Bonus;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class BonusLevelEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('bonusLevel.number')
                ->title(__('platform.bonus.Number'))
                ->type('number')
                ->required()
                ->placeholder(__('platform.bonus.Enter number')),
            Input::make('bonusLevel.title')
                ->title(__('platform.bonus.Title'))
                ->required()
                ->placeholder(__('platform.bonus.Enter title')),
            Input::make('bonusLevel.min_bonus_points')
                ->title(__('platform.bonus.Minimum Bonus Points'))
                ->type('number')
                ->required()
                ->placeholder(__('platform.bonus.Enter minimum bonus points')),
            Input::make('bonusLevel.max_bonus_points')
                ->title(__('platform.bonus.Maximum Bonus Points'))
                ->type('number')
                ->placeholder(__('platform.bonus.Enter maximum bonus points (can be empty)')),
        ];
    }
}
